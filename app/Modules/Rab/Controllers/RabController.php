<?php

namespace App\Modules\Rab\Controllers;

use Throwable;
use DomainException;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;
use App\Http\Controllers\Controller;
use App\Modules\Rab\Services\RabService;
use App\Modules\Rab\Requests\RabCommentStoreRequest;
use App\Modules\Project\Services\ProjectService;
use App\Modules\Rab\Repositories\GenerateInsightRepository;
use App\Modules\Unit\Services\UnitService;

class RabController extends Controller
{
    /**
     * Inisialisasi controller dengan dependency service dan repository.
     */
    public function __construct(
        protected RabService $rabService,
        protected ProjectService $projectService,
        protected UnitService $unitService,
        protected GenerateInsightRepository $rabInsightRepository
    ) {}

    /**
     * Menampilkan halaman RAB.
     *
     * Fitur:
     * - Menampilkan dropdown project
     * - Menampilkan unit untuk kebutuhan form
     * - Generate data RAB jika project dipilih
     * - Menampilkan AI insight jika tersedia
     *
     * Catatan:
     * - Jika project belum dipilih, data default kosong
     */
    public function index(Request $request)
    {
        $projectId = $request->project_id;
        $projects = $this->projectService->getProjects();
        $units = $this->unitService->getUnits(
            null,
            false
        );

        $insight = $projectId
            ? $this->rabInsightRepository->getActive($projectId)
            : null;

        $rab = $projectId
            ? $this->rabService->generate($projectId)
            : [
                'project' => null,
                'summary' => [
                    'material_total' => 0,
                    'wage_total' => 0,
                    'tool_total' => 0,
                    'grand_total' => 0,
                ],
                'detail' => [],
                'recap_material' => [],
                'recap_wage' => [],
                'recap_tool' => [],
            ];

        return Inertia::render('Modules/Rab/Index', [
            'rab' => $rab,

            'projectOptions' => $projects->map(fn($p) => [
                'value' => $p->id,
                'label' => $p->project_name
            ]),

            'unitOptions' => $units->map(fn($unit) => [
                'value' => $unit->name,
                'label' => $unit->name,
            ]),

            'insight' => $insight,

            'filters' => [
                'project_id' => $projectId
            ],
        ]);
    }

    /**
     * Menangani aksi workflow RAB (comment, approve, revision, dll).
     *
     * Catatan:
     * - Validasi menggunakan FormRequest
     * - Error bisnis menggunakan DomainException
     */
    public function action(RabCommentStoreRequest $request)
    {
        try {
            $this->rabService->handleAction($request->validated());

            return back();
        } catch (DomainException $e) {
            return back()->withErrors([
                $e->getMessage()
            ]);
        } catch (Throwable $e) {
            return back()->withErrors([
                'Terjadi kesalahan, silahkan coba lagi'
            ]);
        }
    }

    /**
     * Generate dan menampilkan PDF RAB.
     *
     * Proses:
     * - Generate data RAB
     * - Render ke HTML blade
     * - Convert ke PDF menggunakan Browsershot
     *
     * Output:
     * - PDF ditampilkan inline di browser
     */
    public function pdf(Request $request)
    {
        $projectId = $request->project_id;

        if (!$projectId) {
            abort(404);
        }

        $rab = $this->rabService->generate($projectId);
        $html = view('pdf.rab', compact('rab'))->render();

        $browsershot = Browsershot::html($html)
            ->format('A4')
            ->showBackground();

        if (app()->environment('production')) {
            $browsershot
                ->setNodeBinary('/usr/bin/node')
                ->setChromePath('/usr/bin/chromium')
                ->noSandbox()
                ->addChromiumArguments([
                    '--disable-dev-shm-usage',
                    '--no-sandbox',
                    '--disable-gpu',
                ]);
        }

        $pdf = $browsershot->pdf();

        return response($pdf, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="rab.pdf"');
    }
}
