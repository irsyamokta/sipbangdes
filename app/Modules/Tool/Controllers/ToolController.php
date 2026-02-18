<?php

namespace App\Modules\Tool\Controllers;

use Exception;
use Throwable;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Tool\Services\ToolService;
use App\Modules\Unit\Services\UnitService;
use App\Modules\Tool\Requests\ToolStoreRequest;
use App\Modules\Tool\Requests\ToolUpdateRequest;
use DomainException;

class ToolController extends Controller
{
    public function __construct(
        protected ToolService $service,
        protected UnitService $unitService
    ) {}

    public function index(Request $request)
    {
        $tools = $this->service->getTools($request->search);
        $units = $this->unitService->getUnits();

        return Inertia::render('Modules/Tools/Index', [
            'tools' => $tools,
            'units' => $units->map(fn($unit) => [
                'value' => $unit->name,
                'label' => $unit->name,
            ]),
            'filters' => $request->only(['search', 'page'])
        ]);
    }

    public function store(ToolStoreRequest $request)
    {
        try {
            $this->service->createTool($request->validated());

            return redirect()->back()->with('success', 'Alat berhasil ditambahkan');
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

    public function update(ToolUpdateRequest $request, $id)
    {
        try {
            $this->service->updateTool($id, $request->validated());

            return redirect()->back()->with('success');
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
            $this->service->deleteTool($id);

            return redirect()->back();
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with("error", $e->getMessage());
        }
    }
}
