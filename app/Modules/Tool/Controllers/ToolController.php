<?php

namespace App\Modules\Tool\Controllers;

use Exception;
use Throwable;
use DomainException;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Tool\Services\ToolService;
use App\Modules\Unit\Services\UnitService;
use App\Modules\Tool\Requests\ToolStoreRequest;
use App\Modules\Tool\Requests\ToolUpdateRequest;

class ToolController extends Controller
{
    public function __construct(
        protected ToolService $service,
        protected UnitService $unitService
    ) {}

    public function index(Request $request)
    {
        $tools = $this->service->getTools(
            $request->search,
            true,
            10
        );

        $units = $this->unitService->getUnits(
            null,
            false
        );

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

    public function update(ToolUpdateRequest $request, $id)
    {
        try {
            $this->service->updateTool($id, $request->validated());

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
            $this->service->deleteTool($id);

            return back();
        } catch (Exception $e) {
            return back()->withErrors([
                'Terjadi kesalahan, silahkan coba lagi'
            ]);
        }
    }
}
