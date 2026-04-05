<?php

namespace App\Modules\Rab\Controllers;

use Throwable;
use App\Http\Controllers\Controller;
use App\Modules\Rab\Services\GenerateInsightService;
use App\Modules\Rab\Requests\GenerateInsightRequest;

class GenerateInsightController extends Controller
{
    /**
     * Inisialisasi controller dengan dependency service.
     */
    public function __construct(
        protected GenerateInsightService $insightService
    ) {}

    /**
     * Menjalankan proses generate insight AI.
     *
     * Catatan:
     * - Validasi dilakukan melalui FormRequest
     * - Menggunakan user yang sedang login
     * - Error ditampilkan ke user jika terjadi kegagalan
     */
    public function generate(GenerateInsightRequest $request)
    {
        try {
            $this->insightService->generate(
                $request->project_id,
                auth()->user()
            );

            return back();
        } catch (Throwable $e) {
            return back()->withErrors([
                $e->getMessage()
            ]);
        }
    }
}
