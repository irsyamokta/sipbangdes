<?php

namespace App\Modules\Unit\Controllers;

use Exception;
use Throwable;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Unit\Services\UnitService;
use App\Modules\Unit\Requests\UnitStoreRequest;
use App\Modules\Unit\Requests\UnitUpdateRequest;
use DomainException;

class UnitController extends Controller
{
    public function __construct(
        protected UnitService $service
    ) {}

    public function index(Request $request)
    {
        $units = $this->service->getUnits($request->search);

        return Inertia::render('Modules/Units/Index', [
            'units' => $units,
            'filters' => [
                'search' => $request->search,
            ],
        ]);
    }

    public function store(UnitStoreRequest $request)
    {
        try {
            $this->service->createUnit($request->validated());

            return redirect()->back();
        } catch (DomainException $e) {
            return back()->withErrors([
                'name' => $e->getMessage()
            ]);
        } catch (Throwable $e) {
            report($e);

            return back()->with(
                "error",
                "Terjadi kesalahan sistem, silakan coba lagi"
            );
        }
    }

    public function update(UnitUpdateRequest $request, $id)
    {
        try {
            $this->service->updateUnit($id, $request->validated());

            return redirect()->back();
        } catch (DomainException $e) {
            return back()->withErrors([
                'name' => $e->getMessage()
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
            $this->service->deleteUnit($id);

            return redirect()->back();
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with("error", $e->getMessage());
        }
    }
}
