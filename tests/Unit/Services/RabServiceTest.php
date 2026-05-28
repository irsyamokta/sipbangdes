<?php

namespace Tests\Unit\Services;

use App\Modules\Rab\Repositories\RabRepository;
use App\Modules\Rab\Services\RabService;
use DomainException;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;

class RabServiceTest extends TestCase
{
    protected $rabRepository;
    protected $rabService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rabRepository = Mockery::mock(RabRepository::class);
        $this->rabService = new RabService($this->rabRepository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    // ── handleAction: Planner ──────────────────────────────────────────────

    /**
     * Test penanganan aksi workflow persetujuan data.
     *
     * Skenario:
     * - Pengguna dengan role spesifik memicu aksi tertentu (misal: kirim, review, setujui, revisi).
     * - Sistem memvalidasi apakah role tersebut diizinkan melakukan aksi pada status data saat ini.
     *
     * Ekspektasi:
     * - Status data berubah, atau sistem melempar Exception apabila aksi tidak sah.
     */
    public function test_handle_action_throws_exception_for_invalid_role()
    {
        $projectId = 'proj-1';
        $data = ['project_id' => $projectId, 'action' => 'send'];

        $mockProject = Mockery::mock();
        $mockProject->rab_status = 'draft';
        $mockProject->id = $projectId;

        $this->rabRepository
            ->shouldReceive('getProjectWithRelations')
            ->once()
            ->with($projectId)
            ->andReturn($mockProject);

        $mockUser = Mockery::mock();
        $mockUser->role = 'viewer';

        Auth::shouldReceive('user')->once()->andReturn($mockUser);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Role tidak valid');

        $this->rabService->handleAction($data);
    }

    /**
     * Test penanganan aksi workflow persetujuan data.
     *
     * Skenario:
     * - Pengguna dengan role spesifik memicu aksi tertentu (misal: kirim, review, setujui, revisi).
     * - Sistem memvalidasi apakah role tersebut diizinkan melakukan aksi pada status data saat ini.
     *
     * Ekspektasi:
     * - Status data berubah, atau sistem melempar Exception apabila aksi tidak sah.
     */
    public function test_handle_action_planner_throws_exception_if_already_approved()
    {
        $projectId = 'proj-1';
        $data = ['project_id' => $projectId, 'action' => 'send'];

        $mockProject = Mockery::mock();
        $mockProject->rab_status = 'approved';
        $mockProject->id = $projectId;

        $this->rabRepository
            ->shouldReceive('getProjectWithRelations')
            ->once()
            ->with($projectId)
            ->andReturn($mockProject);

        $mockUser = Mockery::mock();
        $mockUser->role = 'planner';

        Auth::shouldReceive('user')->once()->andReturn($mockUser);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('RAB sudah disetujui');

        $this->rabService->handleAction($data);
    }

    /**
     * Test penanganan aksi workflow persetujuan data.
     *
     * Skenario:
     * - Pengguna dengan role spesifik memicu aksi tertentu (misal: kirim, review, setujui, revisi).
     * - Sistem memvalidasi apakah role tersebut diizinkan melakukan aksi pada status data saat ini.
     *
     * Ekspektasi:
     * - Status data berubah, atau sistem melempar Exception apabila aksi tidak sah.
     */
    public function test_handle_action_planner_throws_exception_if_status_not_sendable()
    {
        $projectId = 'proj-1';
        $data = ['project_id' => $projectId, 'action' => 'send'];

        $mockProject = Mockery::mock();
        $mockProject->rab_status = 'submitted';
        $mockProject->id = $projectId;

        $this->rabRepository
            ->shouldReceive('getProjectWithRelations')
            ->once()
            ->with($projectId)
            ->andReturn($mockProject);

        $mockUser = Mockery::mock();
        $mockUser->role = 'planner';

        Auth::shouldReceive('user')->once()->andReturn($mockUser);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('RAB tidak bisa dikirim');

        $this->rabService->handleAction($data);
    }

    /**
     * Test penanganan aksi workflow persetujuan data.
     *
     * Skenario:
     * - Pengguna dengan role spesifik memicu aksi tertentu (misal: kirim, review, setujui, revisi).
     * - Sistem memvalidasi apakah role tersebut diizinkan melakukan aksi pada status data saat ini.
     *
     * Ekspektasi:
     * - Status data berubah, atau sistem melempar Exception apabila aksi tidak sah.
     */
    public function test_handle_action_planner_throws_exception_if_no_tos()
    {
        $projectId = 'proj-1';
        $data = ['project_id' => $projectId, 'action' => 'send'];

        $mockProject = Mockery::mock();
        $mockProject->rab_status = 'draft';
        $mockProject->id = $projectId;
        $mockProject->takeOffSheets = collect([]);

        $this->rabRepository
            ->shouldReceive('getProjectWithRelations')
            ->once()
            ->with($projectId)
            ->andReturn($mockProject);

        $mockUser = Mockery::mock();
        $mockUser->role = 'planner';

        Auth::shouldReceive('user')->once()->andReturn($mockUser);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Item RAB belum ada');

        $this->rabService->handleAction($data);
    }

    // ── handleAction: Reviewer ─────────────────────────────────────────────

    /**
     * Test penanganan aksi workflow persetujuan data.
     *
     * Skenario:
     * - Pengguna dengan role spesifik memicu aksi tertentu (misal: kirim, review, setujui, revisi).
     * - Sistem memvalidasi apakah role tersebut diizinkan melakukan aksi pada status data saat ini.
     *
     * Ekspektasi:
     * - Status data berubah, atau sistem melempar Exception apabila aksi tidak sah.
     */
    public function test_handle_action_reviewer_throws_exception_if_invalid_status()
    {
        $projectId = 'proj-1';
        $data = ['project_id' => $projectId, 'action' => 'forward'];

        $mockProject = Mockery::mock();
        $mockProject->rab_status = 'draft';
        $mockProject->id = $projectId;

        $this->rabRepository
            ->shouldReceive('getProjectWithRelations')
            ->once()
            ->with($projectId)
            ->andReturn($mockProject);

        $mockUser = Mockery::mock();
        $mockUser->role = 'reviewer';

        Auth::shouldReceive('user')->once()->andReturn($mockUser);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Belum bisa direview');

        $this->rabService->handleAction($data);
    }

    /**
     * Test penanganan aksi workflow persetujuan data.
     *
     * Skenario:
     * - Pengguna dengan role spesifik memicu aksi tertentu (misal: kirim, review, setujui, revisi).
     * - Sistem memvalidasi apakah role tersebut diizinkan melakukan aksi pada status data saat ini.
     *
     * Ekspektasi:
     * - Status data berubah, atau sistem melempar Exception apabila aksi tidak sah.
     */
    public function test_handle_action_reviewer_throws_exception_if_invalid_action()
    {
        $projectId = 'proj-1';
        $data = ['project_id' => $projectId, 'action' => 'approve'];

        $mockProject = Mockery::mock();
        $mockProject->rab_status = 'submitted';
        $mockProject->id = $projectId;

        $this->rabRepository
            ->shouldReceive('getProjectWithRelations')
            ->once()
            ->with($projectId)
            ->andReturn($mockProject);

        $mockUser = Mockery::mock();
        $mockUser->role = 'reviewer';

        Auth::shouldReceive('user')->once()->andReturn($mockUser);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Aksi tidak valid');

        $this->rabService->handleAction($data);
    }

    // ── handleAction: Approver ─────────────────────────────────────────────

    /**
     * Test penanganan aksi workflow persetujuan data.
     *
     * Skenario:
     * - Pengguna dengan role spesifik memicu aksi tertentu (misal: kirim, review, setujui, revisi).
     * - Sistem memvalidasi apakah role tersebut diizinkan melakukan aksi pada status data saat ini.
     *
     * Ekspektasi:
     * - Status data berubah, atau sistem melempar Exception apabila aksi tidak sah.
     */
    public function test_handle_action_approver_throws_exception_if_invalid_status()
    {
        $projectId = 'proj-1';
        $data = ['project_id' => $projectId, 'action' => 'approve'];

        $mockProject = Mockery::mock();
        $mockProject->rab_status = 'submitted';
        $mockProject->id = $projectId;

        $this->rabRepository
            ->shouldReceive('getProjectWithRelations')
            ->once()
            ->with($projectId)
            ->andReturn($mockProject);

        $mockUser = Mockery::mock();
        $mockUser->role = 'approver';

        Auth::shouldReceive('user')->once()->andReturn($mockUser);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Belum bisa diapprove');

        $this->rabService->handleAction($data);
    }

    /**
     * Test penanganan aksi workflow persetujuan data.
     *
     * Skenario:
     * - Pengguna dengan role spesifik memicu aksi tertentu (misal: kirim, review, setujui, revisi).
     * - Sistem memvalidasi apakah role tersebut diizinkan melakukan aksi pada status data saat ini.
     *
     * Ekspektasi:
     * - Status data berubah, atau sistem melempar Exception apabila aksi tidak sah.
     */
    public function test_handle_action_approver_throws_exception_if_invalid_action()
    {
        $projectId = 'proj-1';
        $data = ['project_id' => $projectId, 'action' => 'send'];

        $mockProject = Mockery::mock();
        $mockProject->rab_status = 'reviewed';
        $mockProject->id = $projectId;

        $this->rabRepository
            ->shouldReceive('getProjectWithRelations')
            ->once()
            ->with($projectId)
            ->andReturn($mockProject);

        $mockUser = Mockery::mock();
        $mockUser->role = 'approver';

        Auth::shouldReceive('user')->once()->andReturn($mockUser);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Aksi tidak valid');

        $this->rabService->handleAction($data);
    }

    // ── handleAction: Reviewer forward (success path) ──────────────────────

    /**
     * Test penanganan aksi workflow persetujuan data.
     *
     * Skenario:
     * - Pengguna dengan role spesifik memicu aksi tertentu (misal: kirim, review, setujui, revisi).
     * - Sistem memvalidasi apakah role tersebut diizinkan melakukan aksi pada status data saat ini.
     *
     * Ekspektasi:
     * - Status data berubah, atau sistem melempar Exception apabila aksi tidak sah.
     */
    public function test_handle_action_reviewer_forward_successfully()
    {
        $projectId = 'proj-1';
        $data = ['project_id' => $projectId, 'action' => 'forward'];

        $mockProject = Mockery::mock();
        $mockProject->rab_status = 'submitted';
        $mockProject->id = $projectId;

        $this->rabRepository
            ->shouldReceive('getProjectWithRelations')
            ->once()
            ->with($projectId)
            ->andReturn($mockProject);

        $mockUser = Mockery::mock();
        $mockUser->role = 'reviewer';

        Auth::shouldReceive('user')->once()->andReturn($mockUser);
        Auth::shouldReceive('id')->once()->andReturn(1);

        $this->rabRepository
            ->shouldReceive('updateStatus')
            ->once()
            ->with($projectId, 'reviewed');

        $this->rabRepository
            ->shouldReceive('storeComment')
            ->once();

        $this->rabService->handleAction($data);

        $this->assertTrue(true);
    }
}

