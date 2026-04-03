<?php

namespace App\Modules\User\Repositories;

use App\Models\User;

class UserRepository
{
    /**
     * Mengambil seluruh data user dengan pagination dan filter.
     *
     * Catatan:
     * - Search mencakup name, email, dan role
     * - Query string dipertahankan untuk pagination
     */
    public function getAll(?string $search = null)
    {
        return User::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('role', 'like', "%{$search}%");
                });
            })
            ->paginate(10)
            ->withQueryString();
    }

    /**
     * Mengambil satu user berdasarkan ID.
     *
     * Catatan:
     * - Menggunakan findOrFail untuk menjamin data tersedia
     */
    public function find($id)
    {
        return User::findOrFail($id);
    }

    /**
     * Mengecek apakah email sudah digunakan.
     *
     * Digunakan untuk proses create.
     */
    public function existsByEmail(string $email)
    {
        return User::where('email', $email)->exists();
    }

    /**
     * Mengecek duplikasi email (exclude ID tertentu).
     *
     * Digunakan untuk proses update.
     */
    public function existsByEmailExcept($id, string $email)
    {
        return User::where('email', $email)
            ->where('id', '!=', $id)
            ->exists();
    }

    /**
     * Menyimpan user baru.
     *
     * Catatan:
     * - Validasi dilakukan di layer service
     */
    public function create(array $data)
    {
        return User::create($data);
    }

    /**
     * Memperbarui data user.
     */
    public function update(User $user, array $data)
    {
        return $user->update($data);
    }

    /**
     * Menghapus user.
     */
    public function delete(User $user)
    {
        return $user->delete();
    }
}
