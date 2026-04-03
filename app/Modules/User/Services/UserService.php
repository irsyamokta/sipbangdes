<?php

namespace App\Modules\User\Services;

use DomainException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Modules\User\Repositories\UserRepository;

class UserService
{
    /**
     * Inisialisasi service dengan dependency repository.
     */
    public function __construct(
        protected UserRepository $userRepository
    ) {}

    /**
     * Mengambil data user dengan filter pencarian opsional.
     *
     * Catatan:
     * - Menggunakan pagination dari repository
     */
    public function getUsers(?string $search = null)
    {
        return $this->userRepository->getAll($search);
    }

    /**
     * Membuat user baru.
     *
     * Aturan bisnis:
     * - Email harus unik
     *
     * Catatan:
     * - Password di-hash sebelum disimpan
     * - email_verified_at langsung di-set (auto verified)
     * - Role di-assign setelah user dibuat
     * - Menggunakan transaction untuk menjaga konsistensi
     */
    public function createUser(array $data)
    {
        if ($this->userRepository->existsByEmail($data['email']))
            throw new DomainException("Email sudah terdaftar.");

        return DB::transaction(function () use ($data) {

            $role = $data['role'] ?? null;

            $user = $this->userRepository->create([
                ...$data,
                "password" => Hash::make($data["password"]),
                "email_verified_at" => now(),
            ]);

            $user->assignRole($role);

            return $user;
        });
    }

    /**
     * Memperbarui data user.
     *
     * Aturan bisnis:
     * - Email harus tetap unik (kecuali user itu sendiri)
     *
     * Catatan:
     * - Password hanya di-update jika diisi
     * - Password akan di-hash sebelum disimpan
     */
    public function updateUser($id, array $data)
    {
        $user = $this->userRepository->find($id);

        if (
            isset($data['email']) &&
            $this->userRepository->existsByEmailExcept($id, $data['email'])
        )
            throw new DomainException("Email sudah terdaftar.");

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        return $this->userRepository->update($user, $data);
    }

    /**
     * Menghapus user.
     *
     * Aturan bisnis:
     * - Tidak dapat menghapus akun sendiri
     */
    public function deleteUser($id)
    {
        $user = $this->userRepository->find($id);

        if ($user->id === Auth::id()) {
            throw new DomainException('Tidak dapat menghapus akun sendiri.');
        }

        return $this->userRepository->delete($user);
    }
}
