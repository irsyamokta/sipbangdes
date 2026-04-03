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
    /**
     * Inisialisasi controller dengan dependency service.
     */
    public function __construct(
        protected UserService $userService
    ) {}

    /**
     * Menampilkan halaman daftar user.
     *
     * Catatan:
     * - Menggunakan pagination
     * - Filter (search & page) dikirim ke frontend
     */
    public function index(Request $request)
    {
        $users = $this->userService->getUsers($request->search);

        return Inertia::render('Modules/Users/Index', [
            'users' => $users,
            'filters' => $request->only(['search', 'page']),
        ]);
    }


    /**
     * Menyimpan user baru.
     *
     * Catatan:
     * - Validasi dilakukan di FormRequest
     * - DomainException untuk error bisnis
     */
    public function store(UserStoreRequest $request)
    {
        try {
            $this->userService->createUser($request->validated());

            return back();
        } catch (DomainException $e) {
            return back()->withErrors([
                'email' => $e->getMessage()
            ]);
        } catch (Throwable $e) {
            return back()->withErrors([
                'Terjadi kesalahan, silahkan coba lagi'
            ]);
        }
    }

    /**
     * Memperbarui data user.
     *
     * Catatan:
     * - Error bisnis dan error sistem dipisahkan
     */
    public function update(UserUpdateRequest $request, $id)
    {
        try {
            $this->userService->updateUser($id, $request->validated());

            return back();
        } catch (DomainException $e) {
            return back()->withErrors([
                'email' => $e->getMessage()
            ]);
        } catch (Throwable $e) {
            return back()->withErrors([
                'Terjadi kesalahan, silahkan coba lagi'
            ]);
        }
    }

    /**
     * Menghapus user.
     */
    public function destroy($id)
    {
        try {
            $this->userService->deleteUser($id);

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
