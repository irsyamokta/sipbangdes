<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Modules\Rab\Repositories\GenerateInsightRepository;
use App\Modules\Rab\Services\GenerateInsightService;
use App\Modules\Rab\Services\RabService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Mockery;
use Tests\TestCase;

class GenerateInsightServiceTest extends TestCase
{
    protected $rabService;
    protected $insightRepository;
    protected $generateInsightService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rabService = Mockery::mock(RabService::class);
        $this->insightRepository = Mockery::mock(GenerateInsightRepository::class);

        $this->generateInsightService = new GenerateInsightService(
            $this->rabService,
            $this->insightRepository
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test operasi terkait data.
     *
     * Skenario:
     * - Parameter dan mock data disiapkan untuk pengujian operasi spesifik.
     * - Proses servis utama dipanggil.
     *
     * Ekspektasi:
     * - Fungsi tereksekusi dengan mengembalikan nilai yang tepat atau merubah state internal dengan benar.
     */
    public function test_generate_throws_exception_if_limit_exceeded()
    {
        $projectId = 'proj-1';
        $user = new User();
        $user->id = '550e8400-e29b-41d4-a716-446655440001';

        DB::shouldReceive('transaction')
            ->once()
            ->andReturnUsing(function ($callback) {
                return $callback();
            });

        $this->insightRepository
            ->shouldReceive('countByProject')
            ->once()
            ->with($projectId)
            ->andReturn(3);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Maksimal hanya 3 kali untuk proyek ini');

        $this->generateInsightService->generate($projectId, $user);
    }
}

