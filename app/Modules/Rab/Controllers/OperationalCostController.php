<?php

namespace App\Modules\Rab\Controllers;

use Throwable;
use DomainException;
use App\Http\Controllers\Controller;
use App\Modules\Rab\Requests\OperationalCostRequest;
use App\Modules\Rab\Services\OperationalCostService;

class OperationalCostController extends Controller
{
    public function __construct(
        protected OperationalCostService $service
    ) {}

    public function store(OperationalCostRequest $request)
    {
        try {
            $this->service->store($request->validated());
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

    public function update(OperationalCostRequest $request, $id)
    {
        try {
            $this->service->update($id, $request->validated());
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

    public function destroy($id)
    {
        try {
            $this->service->destroy($id);
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
