<?php

namespace App\Modules\User\Services;

use Exception;
use Illuminate\Support\Facades\Hash;
use App\Modules\User\Repositories\UserRepository;

class UserService
{
    public function __construct(
        protected UserRepository $repo
    ) {}

    public function getUsers(?string $search = null)
    {
        return $this->repo->getAll($search);
    }

    public function createUser(array $data)
    {
        try {
            $role = $data["role"];

            $user = $this->repo->create([
                ...$data,
                "password" => Hash::make($data["password"]),
                "email_verified_at" => now(),
            ]);

            $user->assignRole($role);

            return $user;
        } catch (Exception $e) {
            throw new Exception("Gagal membuat pengguna: " . $e->getMessage());
        }
    }

    public function updateUser($id, array $data)
    {
        try {
            $user = $this->repo->find($id);

            if (empty($data["password"])) {
                unset($data["password"]);
            } else {
                $data["password"] = Hash::make($data["password"]);
            }

            return $this->repo->update($user, $data);
        } catch (Exception $e) {
            throw new Exception("Gagal update pengguna: " . $e->getMessage());
        }
    }

    public function deleteUser($id)
    {
        try {
            $user = $this->repo->find($id);

            return $this->repo->delete($user);
        } catch (Exception $e) {
            throw new Exception("Gagal hapus pengguna: " . $e->getMessage());
        }
    }
}
