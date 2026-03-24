<?php

namespace App\Modules\Ahsp\Controllers;

use Throwable;
use DomainException;
use App\Http\Controllers\Controller;
use App\Modules\Ahsp\Services\AhspToolService;
use App\Modules\Tool\Services\ToolService;
use App\Modules\Ahsp\Requests\AhspToolStoreRequest;
use App\Modules\Ahsp\Requests\AhspToolUpdateRequest;


class AhspToolController extends Controller
{
    public function __construct(
        protected AhspToolService $service,
        protected ToolService $toolService
    ) {}

    public function store(AhspToolStoreRequest $request)
    {
        try {
            $this->service->createAhspTool($request->validated());

            return back();
        } catch (DomainException $e) {
            return back()->withErrors([
                'ahsp_id' => $e->getMessage()
            ]);
        } catch (Throwable $e) {
            return back()->withErrors([
                'Terjadi kesalahan, silahkan coba lagi'
            ]);
        }
    }

    public function update(AhspToolUpdateRequest $request, $id)
    {
        try {
            $this->service->updateAhspTool($id, $request->validated());

            return back();
        } catch (DomainException $e) {
            return back()->withErrors([
                'ahsp_id' => $e->getMessage()
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
            $this->service->deleteAhspTool($id);

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
