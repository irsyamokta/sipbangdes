<?php

namespace App\Modules\Ahsp\Controllers;

use Throwable;
use DomainException;
use App\Http\Controllers\Controller;
use App\Modules\Ahsp\Services\AhspMaterialService;
use App\Modules\Material\Services\MaterialService;
use App\Modules\Ahsp\Requests\AhspMaterialStoreRequest;
use App\Modules\Ahsp\Requests\AhspMaterialUpdateRequest;


class AhspMaterialController extends Controller
{
    public function __construct(
        protected AhspMaterialService $service,
        protected MaterialService $materialService
    ) {}

    public function store(AhspMaterialStoreRequest $request)
    {
        try {
            $this->service->createAhspMaterial($request->validated());

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

    public function update(AhspMaterialUpdateRequest $request, $id)
    {
        try {
            $this->service->updateAhspMaterial($id, $request->validated());

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
            $this->service->deleteAhspMaterial($id);

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
