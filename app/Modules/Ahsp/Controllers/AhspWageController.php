<?php

namespace App\Modules\Ahsp\Controllers;

use Throwable;
use DomainException;
use App\Http\Controllers\Controller;
use App\Modules\Ahsp\Services\AhspWageService;
use App\Modules\Wage\Services\WageService;
use App\Modules\Ahsp\Requests\AhspWageStoreRequest;
use App\Modules\Ahsp\Requests\AhspWageUpdateRequest;


class AhspWageController extends Controller
{
    public function __construct(
        protected AhspWageService $service,
        protected WageService $wageService
    ) {}

    public function store(AhspWageStoreRequest $request)
    {
        try {
            $this->service->createAhspWage($request->validated());

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

    public function update(AhspWageUpdateRequest $request, $id)
    {
        try {
            $this->service->updateAhspWage($id, $request->validated());

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
            $this->service->deleteAhspWage($id);

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
