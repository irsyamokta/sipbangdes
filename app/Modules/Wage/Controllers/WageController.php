<?php

namespace App\Modules\Wage\Controllers;

use Exception;
use Throwable;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Wage\Services\WageService;
use App\Modules\Unit\Services\UnitService;
use App\Modules\Wage\Requests\WageStoreRequest;
use App\Modules\Wage\Requests\WageUpdateRequest;
use DomainException;

class WageController extends Controller
{
    public function __construct(
        protected WageService $service,
        protected UnitService $unitService
    ) {}

    public function index(Request $request)
    {
        $wages = $this->service->getWages($request->search);
        $units = $this->unitService->getUnits();

        return Inertia::render('Modules/Wages/Index', [
            'wages' => $wages,
            'units' => $units->map(fn($unit) => [
                'value' => $unit->name,
                'label' => $unit->name,
            ]),
            'filters' => $request->only(['search', 'page'])
        ]);
    }

    public function store(WageStoreRequest $request)
    {
        try {
            $this->service->createWage($request->validated());

            return back();
        } catch (DomainException $e) {
            return back()->withErrors([
                'position' => $e->getMessage()
            ]);
        } catch (Throwable $e) {
            report($e);

            return back()->with(
                "error",
                "Terjadi kesalahan sistem, silakan coba lagi"
            );
        }
    }

    public function update(WageUpdateRequest $request, $id)
    {
        try {
            $this->service->updateWage($id, $request->validated());

            return back();
        } catch (DomainException $e) {
            return back()->withErrors([
                'position' => $e->getMessage()
            ]);
        } catch (Throwable $e) {
            report($e);

            return back()->with(
                "error",
                "Terjadi kesalahan sistem, silakan coba lagi"
            );
        }
    }

    public function destroy($id)
    {
        try {
            $this->service->deleteWage($id);

            return back();
        } catch (Exception $e) {
            return back()->with(
                "error",
                "Terjadi kesalahan sistem, silakan coba lagi"
            );
        }
    }
}
