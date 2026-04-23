<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Modules\User\Repositories\UserRepository;
use App\Modules\User\Services\UserService;
use DomainException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mockery;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    protected $userRepository;

    protected $userService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = Mockery::mock(UserRepository::class);

        $this->userService = new UserService(
            $this->userRepository
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }


    public function test_create_user_throw_exception_if_email_exists()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'test@mail.com',
            'password' => '123456',
            'role' => 'admin',
        ];

        $this->userRepository
            ->shouldReceive('existsByEmail')
            ->once()
            ->with($data['email'])
            ->andReturn(true);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Email sudah terdaftar.');

        $this->userService->createUser($data);
    }

    public function test_create_user_successfully()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'test@mail.com',
            'password' => '123456',
            'role' => 'admin',
        ];

        DB::shouldReceive('transaction')
            ->once()
            ->andReturnUsing(function ($callback) {
                return $callback();
            });

        $userMock = Mockery::mock(User::class);

        $userMock
            ->shouldReceive('assignRole')
            ->once()
            ->with('admin');

        $this->userRepository
            ->shouldReceive('existsByEmail')
            ->once()
            ->with($data['email'])
            ->andReturn(false);

        $this->userRepository
            ->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($arg) {
                return isset($arg['password'])
                    && isset($arg['email_verified_at']);
            }))
            ->andReturn($userMock);

        $result = $this->userService->createUser($data);

        $this->assertEquals($userMock, $result);
    }


    public function test_update_user_throw_exception_if_email_duplicate()
    {
        $userId = 1;

        $data = [
            'email' => 'duplicate@mail.com',
        ];

        $userMock = Mockery::mock(User::class);

        $this->userRepository
            ->shouldReceive('find')
            ->once()
            ->with($userId)
            ->andReturn($userMock);

        $this->userRepository
            ->shouldReceive('existsByEmailExcept')
            ->once()
            ->with($userId, 'duplicate@mail.com')
            ->andReturn(true);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Email sudah terdaftar.');

        $this->userService->updateUser($userId, $data);
    }

    public function test_update_user_without_password()
    {
        $userId = 1;

        $data = [
            'name' => 'John Doe',
            'email' => 'new@mail.com',
            'password' => '',
        ];

        $userMock = Mockery::mock(User::class);

        $this->userRepository
            ->shouldReceive('find')
            ->once()
            ->with($userId)
            ->andReturn($userMock);

        $this->userRepository
            ->shouldReceive('existsByEmailExcept')
            ->once()
            ->with($userId, 'new@mail.com')
            ->andReturn(false);

        $this->userRepository
            ->shouldReceive('update')
            ->once()
            ->with($userMock, Mockery::on(function ($arg) {
                return ! isset($arg['password']);
            }))
            ->andReturn(true);

        $result = $this->userService->updateUser($userId, $data);

        $this->assertTrue($result);
    }

    public function test_update_user_with_password()
    {
        $userId = 1;

        $data = [
            'name' => 'Jane Doe',
            'email' => 'new@mail.com',
            'password' => 'newpassword',
        ];

        $userMock = Mockery::mock(User::class);

        $this->userRepository
            ->shouldReceive('find')
            ->once()
            ->with($userId)
            ->andReturn($userMock);

        $this->userRepository
            ->shouldReceive('existsByEmailExcept')
            ->once()
            ->with($userId, 'new@mail.com')
            ->andReturn(false);

        $this->userRepository
            ->shouldReceive('update')
            ->once()
            ->with($userMock, Mockery::on(function ($arg) {
                return isset($arg['password']);
            }))
            ->andReturn(true);

        $result = $this->userService->updateUser($userId, $data);

        $this->assertTrue($result);
    }

    public function test_delete_user_cannot_delete_self()
    {
        $userId = '550e8400-e29b-41d4-a716-446655440000';

        $user = new User;
        $user->id = $userId;

        Auth::shouldReceive('id')
            ->once()
            ->andReturn($userId);

        $this->userRepository
            ->shouldReceive('find')
            ->once()
            ->with($userId)
            ->andReturn($user);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Tidak dapat menghapus akun sendiri.');

        $this->userService->deleteUser($userId);
    }

    public function test_delete_user_successfully()
    {
        $userId = '550e8400-e29b-41d4-a716-446655440001';

        $authId = '550e8400-e29b-41d4-a716-446655440002';

        $user = new User;
        $user->id = $userId;

        Auth::shouldReceive('id')
            ->once()
            ->andReturn($authId);

        $this->userRepository
            ->shouldReceive('find')
            ->once()
            ->with($userId)
            ->andReturn($user);

        $this->userRepository
            ->shouldReceive('delete')
            ->once()
            ->with($user)
            ->andReturn(true);

        $result = $this->userService->deleteUser($userId);

        $this->assertTrue($result);
    }
}
