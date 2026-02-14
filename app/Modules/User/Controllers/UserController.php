<?php

namespace App\Modules\User\Controllers;

use Exception;
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
            'filters' => [
                'search' => $request->search,
            ],
        ]);
    }

    public function store(UserStoreRequest $request)
    {
        try {
            $this->service->createUser($request->validated());

            return redirect()
                ->back()
                ->with("success", "Pengguna berhasil dibuat");
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with("error", $e->getMessage());
        }
    }

    public function update(UserUpdateRequest $request, $id)
    {
        try {
            $this->service->updateUser($id, $request->validated());

            return redirect()
                ->back()
                ->with("success", "Pengguna berhasil diperbarui");
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with("error", $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->service->deleteUser($id);

            return redirect()
                ->back()
                ->with("success", "Pengguna berhasil dihapus");
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with("error", $e->getMessage());
        }
    }
}
