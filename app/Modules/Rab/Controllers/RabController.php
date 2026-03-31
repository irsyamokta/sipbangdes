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
use App\Modules\Unit\Services\UnitService;

class RabController extends Controller
{
    public function __construct(
        protected RabService $service,
        protected ProjectService $projectService,
        protected UnitService $unitService
    ) {}

    public function index(Request $request)
    {
        $projectId = $request->project_id;
        $projects = $this->projectService->getProjects();
        $units = $this->unitService->getUnits(
            null,
            false
        );

        $rab = $projectId
            ? $this->service->generate($projectId)
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

            'filters' => [
                'project_id' => $projectId
            ],
        ]);
    }

    public function action(RabCommentStoreRequest $request)
    {
        try {
            $this->service->handleAction($request->validated());

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

    public function pdf(Request $request)
    {
        $projectId = $request->project_id;

        if (!$projectId) {
            abort(404);
        }

        $rab = $this->service->generate($projectId);
        $html = view('pdf.rab', compact('rab'))->render();

        $pdf = Browsershot::html($html)
            ->format('A4')
            ->showBackground()
            ->pdf();

        return response($pdf, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="rab.pdf"');
    }
}
