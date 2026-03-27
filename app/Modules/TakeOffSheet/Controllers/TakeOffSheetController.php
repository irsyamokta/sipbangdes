<?php

namespace App\Modules\TakeOffSheet\Controllers;

use Throwable;
use DomainException;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Project\Services\ProjectService;
use App\Modules\WorkerCategory\Services\WorkerCategoryService;
use App\Modules\Ahsp\Services\AhspService;
use App\Modules\Unit\Services\UnitService;
use App\Modules\TakeOffSheet\Services\TakeOffSheetService;
use App\Modules\TakeOffSheet\Requests\TakeOffSheetStoreRequest;
use App\Modules\TakeOffSheet\Requests\TakeOffSheetUpdateRequest;

class TakeOffSheetController extends Controller
{
    public function __construct(
        protected TakeOffSheetService $service,
        protected ProjectService $projectService,
        protected WorkerCategoryService $workerCategoryService,
        protected AhspService $ahspService,
        protected UnitService $unitService
    ) {}

    public function index(Request $request)
    {
        $data = $this->service->getTakeOffSheets(
            $request->search,
            $request->project_id
        );

        return Inertia::render('Modules/TakeOffSheet/Index', [
            'takeOffSheets' => $data,

            'projectOptions' => $this->projectService->getProjects()->map(fn($project) => [
                'value' => $project->id,
                'label' => $project->project_name
            ]),

            'workerCategoryOptions' => $this->workerCategoryService->getWorkerCategory()->map(fn($workerCategory) => [
                'value' => $workerCategory->id,
                'label' => $workerCategory->name
            ]),

            'ahspOptions' => $this->ahspService->getAhsp(null)->map(fn($ahsp) => [
                'value' => $ahsp->id,
                'label' => $ahsp->work_name
            ]),

            'unitOptions' => $this->unitService->getUnits(
                null,
                false,
                true
            )->map(fn($unit) => [
                'value' => $unit->name,
                'label' => $unit->name
            ]),

            'filters' => $request->only([
                'search',
                'project_id',
            ]),
        ]);
    }

    public function store(TakeOffSheetStoreRequest $request)
    {
        try {
            $this->service->create($request->validated());

            return back();
        } catch (DomainException $e) {
            return back()->withErrors([
                'work_name' => $e->getMessage()
            ]);
        } catch (Throwable $e) {
            return back()->withErrors([
                'Terjadi kesalahan, silahkan coba lagi'
            ]);
        }
    }

    public function update(TakeOffSheetUpdateRequest $request, $id)
    {
        try {
            $this->service->update($id, $request->validated());

            return back();
        } catch (DomainException $e) {
            return back()->withErrors([
                'work_name' => $e->getMessage()
            ]);
        } catch (Throwable $e) {
            return back()->withErrors([
                'Terjadi kesalahan, silahkan coba lagi'
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $this->service->delete($id);

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
}
