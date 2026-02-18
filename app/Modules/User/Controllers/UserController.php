<?php

namespace App\Modules\User\Controllers;

use Throwable;
use DomainException;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\User\Services\UserService;
use App\Modules\User\Requests\UserStoreRequest;
use App\Modules\User\Requests\UserUpdateRequest;

class UserController extends Controller
{
    public function __construct(
        protected UserService $service
    ) {}

    public function index(Request $request)
    {
        $users = $this->service->getUsers($request->search);

        return Inertia::render('Modules/Users/Index', [
            'users' => $users,
            'filters' => $request->only(['search', 'page']),
        ]);
    }

    public function store(UserStoreRequest $request)
    {
        try {
            $this->service->createUser($request->validated());

            return back();
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

    public function update(UserUpdateRequest $request, $id)
    {
        try {
            $this->service->updateUser($id, $request->validated());

            return back();
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
            $this->service->deleteUser($id);

            return back();
        } catch (Throwable $e) {
            report($e);

            return back()->with(
                "error",
                "Terjadi kesalahan sistem, silakan coba lagi"
            );
        }
    }
}
