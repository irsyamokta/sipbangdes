<?php

namespace App\Modules\WorkerCategory\Controllers;

use Throwable;
use DomainException;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Modules\WorkerCategory\Services\WorkerCategoryService;
use App\Modules\Ahsp\Services\AhspService;
use App\Modules\Unit\Services\UnitService;
use App\Modules\WorkerCategory\Requests\WorkerCategoryStoreRequest;
use App\Modules\WorkerCategory\Requests\WorkerCategoryUpdateRequest;

class WorkerCategoryController extends Controller
{
    public function __construct(
        protected WorkerCategoryService $service,
        protected UnitService $unitService,
        protected AhspService $ahspService
    ) {}

    public function index(WorkerCategoryService $service)
    {
        $workerCategories = $service->getWorkerCategory();
        $ahsp = $this->ahspService->getAhsp(null);
        $units = $this->unitService->getUnits(
            null,
            false
        );

        return Inertia::render('Modules/WorkerCategory/Index', [
            'workerCategories' => $workerCategories,
            'unitOptions' => $units->map(fn($unit) => [
                'value' => $unit->name,
                'label' => $unit->name
            ]),
            'ahspOptions' => $ahsp->map(fn($ahsp) => [
                'value' => $ahsp->id,
                'label' => $ahsp->work_code . ' - ' . $ahsp->work_name,
            ])
        ]);
    }

    public function store(WorkerCategoryStoreRequest $request)
    {
        try {
            $this->service->createWorkerCategory($request->validated());

            return back();
        } catch (DomainException $e) {
            return back()->withErrors([
                'name' => $e->getMessage()
            ]);
        } catch (Throwable $e) {
            return back()->withErrors([
                'Terjadi kesalahan, silahkan coba lagi'
            ]);
        }
    }

    public function update(WorkerCategoryUpdateRequest $request, $id)
    {
        try {
            $this->service->updateWorkerCategory($id, $request->validated());

            return back();
        } catch (DomainException $e) {
            return back()->withErrors([
                'name' => $e->getMessage()
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
            $this->service->deleteWorkerCategory($id);

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
