<?php

namespace App\Modules\Rab\Services;

use Throwable;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Modules\Rab\Repositories\GenerateInsightRepository;

class GenerateInsightService
{
    /**
     * Inisialisasi service dengan dependency RAB service dan repository insight.
     */
    public function __construct(
        protected RabService $rabService,
        protected GenerateInsightRepository $insightRepository
    ) {}

    /**
     * Generate insight AI untuk suatu project.
     *
     * Alur:
     * - Validasi jumlah generate (maksimal 3x per project)
     * - Generate data RAB
     * - Transform data menjadi payload ringan
     * - Build prompt untuk AI
     * - Call API Gemini
     * - Nonaktifkan insight lama
     * - Simpan insight baru
     *
     * Catatan:
     * - Menggunakan database transaction untuk konsistensi data
     * - Insight lama akan di-set non-aktif
     */
    public function generate(string $projectId, $user)
    {
        return DB::transaction(function () use ($projectId, $user) {

            $count = $this->insightRepository->countByProject($projectId);

            if ($count >= 3) {
                throw new Exception("Maksimal hanya 3 kali untuk proyek ini");
            }

            $rab = $this->rabService->generate($projectId);

            if (empty($rab['detail'])) {
                throw new Exception("RAB belum memiliki detail pekerjaan");
            }

            $payload = $this->transformPayload($rab);

            $prompt = $this->buildPrompt($payload);

            $result = $this->callGemini($prompt);

            $this->insightRepository->deactivateByProject($projectId);

            return $this->insightRepository->create([
                'project_id' => $projectId,
                'insight_content' => $result,
                'generated_by' => $user->id,
                'is_active' => true,
            ]);
        });
    }

    /**
     * Transform data RAB menjadi payload yang lebih ringkas untuk AI.
     *
     * Struktur:
     * - Summary
     * - Detail pekerjaan (dibatasi 20 item)
     * - Top material, upah, dan alat
     * - Biaya operasional
     *
     * Tujuan:
     * - Mengurangi ukuran payload
     * - Fokus pada data penting untuk analisis
     */
    private function transformPayload(array $rab): array
    {
        return [
            'summary' => $rab['summary'],

            'detail' => collect($rab['detail'])
                ->map(fn($item) => [
                    'work_name' => $item['work_name'],
                    'volume' => $item['volume'],
                    'subtotal' => $item['subtotal'],
                ])
                ->take(20),

            'material' => $this->topItems($rab['recap_material']),
            'wage' => $this->topItems($rab['recap_wage']),
            'tool' => $this->topItems($rab['recap_tool']),

            'operational' => $rab['operational'],
        ];
    }

    /**
     * Mengambil item dengan total terbesar.
     *
     * Parameter:
     * - items: data array
     * - limit: jumlah maksimal item (default 10)
     *
     * Digunakan untuk:
     * - Material
     * - Upah
     * - Alat
     */
    private function topItems(array $items, int $limit = 10)
    {
        return collect($items)
            ->sortByDesc('total')
            ->take($limit)
            ->values()
            ->toArray();
    }

    /**
     * Membuat prompt untuk AI (Gemini).
     *
     * Isi:
     * - Role AI sebagai Quantity Surveyor
     * - Format output markdown yang ketat
     * - Aturan penulisan (bullet, heading, dll)
     * - Data RAB dalam bentuk JSON
     *
     * Catatan:
     * - Prompt dirancang agar output konsisten dan mudah dirender
     */
    private function buildPrompt(array $data)
    {
        return trim("
            Anda adalah Quantity Surveyor profesional.

            Tugas Anda adalah menganalisis data RAB dan menghasilkan insight dalam format MARKDOWN yang RAPI, TERSTRUKTUR, dan MUDAH DIBACA.

            ======================================================================

            FORMAT WAJIB (IKUTI PERSIS):

            ## 📊 Ringkasan Proyek
            - Jelaskan gambaran umum proyek
            - Soroti total biaya dan komposisinya
            - Highlight komponen biaya terbesar

            ## 🧱 Analisis Material
            - Gunakan bullet points
            - Identifikasi material dengan biaya terbesar
            - Jelaskan potensi pemborosan atau efisiensi

            ## 👷 Analisis Tenaga Kerja
            - Gunakan bullet points
            - Analisis distribusi tenaga kerja
            - Soroti jika ada ketidakseimbangan biaya

            ## 🔧 Analisis Alat
            - Gunakan bullet points
            - Jelaskan penggunaan alat dominan
            - Identifikasi potensi overuse atau inefficiency

            ## 📅 Rekomendasi Pelaksanaan
            1. Gunakan numbering (1,2,3)
            2. Berikan langkah konkret dan actionable
            3. Fokus pada efisiensi & optimalisasi

            ## ⚠️ Risiko Proyek
            - Gunakan bullet points
            - Sebutkan risiko biaya, operasional, atau teknis
            - Jelaskan dampaknya secara singkat

            ## 💡 Efisiensi Anggaran
            - Gunakan bullet points
            - Berikan saran penghematan yang konkret
            - Fokus pada cost reduction & optimization

            ======================================================================

            ATURAN PENULISAN:

            - Gunakan Bahasa Indonesia formal
            - WAJIB gunakan markdown heading (##)
            - WAJIB gunakan bullet (-)
            - Setiap poin HARUS di baris baru
            - Beri jarak antar section (1 baris kosong)
            - JANGAN tampilkan JSON atau data mentah
            - Fokus pada ANALISIS, bukan mengulang data
            - Gunakan kalimat singkat, jelas, dan profesional
            - Setiap section HARUS dipisahkan dengan SATU baris kosong
            - Jangan menulis dalam satu paragraf panjang
            - Setiap bullet HARUS di baris baru (gunakan newline \n)
            - Output WAJIB valid markdown (bisa dirender oleh ReactMarkdown)

            ======================================================================

            DATA RAB:
            " . json_encode($data, JSON_PRETTY_PRINT));
    }

    /**
     * Memanggil API Gemini untuk generate insight.
     *
     * Proses:
     * - Mengambil API key dan model dari config
     * - Melakukan HTTP request dengan retry (3x)
     * - Timeout 60 detik
     * - Parsing response AI
     *
     * Error handling:
     * - Log error ke sistem
     * - Throw exception jika gagal
     */
    private function callGemini(string $prompt): string
    {
        $apiKey = config('services.gemini.key');
        $model = config('services.gemini.model');

        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

        try {
            $response = retry(3, function () use ($url, $prompt) {
                return Http::timeout(60)->post($url, [
                    "contents" => [
                        [
                            "parts" => [
                                ["text" => $prompt]
                            ]
                        ]
                    ]
                ]);
            }, 1000);

            if (!$response->successful()) {
                throw new Exception($response->body());
            }

            return data_get($response->json(), 'candidates.0.content.parts.0.text');
        } catch (Throwable $e) {
            Log::error('Gemini Error', [
                'message' => $e->getMessage()
            ]);

            throw new Exception("Gagal generate insight AI");
        }
    }
}
