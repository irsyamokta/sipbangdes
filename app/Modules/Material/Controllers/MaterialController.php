<?php

namespace App\Modules\Material\Controllers;

use Exception;
use Throwable;
use DomainException;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Material\Services\MaterialService;
use App\Modules\Unit\Services\UnitService;
use App\Modules\Material\Requests\MaterialStoreRequest;
use App\Modules\Material\Requests\MaterialUpdateRequest;

class MaterialController extends Controller
{
    public function __construct(
        protected MaterialService $service,
        protected UnitService $unitService
    ) {}

    public function index(Request $request)
    {
        $materials = $this->service->getMaterials(
            $request->search,
            true,
            10
        );

        $units = $this->unitService->getUnits(
            null,
            false
        );

        return Inertia::render('Modules/Materials/Index', [
            'materials' => $materials,
            'units' => $units->map(fn($unit) => [
                'value' => $unit->name,
                'label' => $unit->name,
            ]),
            'filters' => $request->only(['search', 'page']),
        ]);
    }

    public function store(MaterialStoreRequest $request)
    {
        try {
            $this->service->createMaterial($request->validated());

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

    public function update(MaterialUpdateRequest $request, $id)
    {
        try {
            $this->service->updateMaterial($id, $request->validated());

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
            $this->service->deleteMaterial($id);

            return back();
        } catch (Exception $e) {
            return back()->withErrors([
                'Terjadi kesalahan, silahkan coba lagi'
            ]);
        }
    }
}
