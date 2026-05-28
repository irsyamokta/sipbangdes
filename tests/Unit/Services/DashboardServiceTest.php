<?php

namespace Tests\Unit\Services;

use App\Modules\Dashboard\Repositories\DashboardRepository;
use App\Modules\Dashboard\Services\DashboardService;
use Mockery;
use Tests\TestCase;

class DashboardServiceTest extends TestCase
{
    protected $dashboardRepository;
    protected $dashboardService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dashboardRepository = Mockery::mock(DashboardRepository::class);

        $this->dashboardService = new DashboardService(
            $this->dashboardRepository
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test pengambilan data dashboard.
     *
     * Skenario:
     * - Method pemanggilan data dieksekusi dengan parameter yang dibutuhkan.
     * - Repository mencari dan mengembalikan sekumpulan data.
     *
     * Ekspektasi:
     * - Service mengembalikan data dashboard secara utuh yang diharapkan.
     */
    public function test_get_dashboard_data_returns_correct_structure()
    {
        $this->dashboardRepository->shouldReceive('getTotalProject')->once()->andReturn(10);
        $this->dashboardRepository->shouldReceive('getActiveProject')->once()->andReturn(5);
        $this->dashboardRepository->shouldReceive('getTotalAhsp')->once()->andReturn(20);
        $this->dashboardRepository->shouldReceive('getTotalTos')->once()->andReturn(30);

        $this->dashboardRepository->shouldReceive('getApprovedRabPerYear')->once()->andReturn(collect([2026 => 5000000]));
        $this->dashboardRepository->shouldReceive('getLatestProjects')->once()->andReturn(collect(['Project A', 'Project B']));
        $this->dashboardRepository->shouldReceive('getTopWorkerCategories')->once()->andReturn(collect(['Tukang', 'Mandor']));

        $result = $this->dashboardService->getDashboardData();

        $this->assertIsArray($result);
        
        $this->assertArrayHasKey('summary', $result);
        $this->assertEquals(10, $result['summary']['total_project']);
        $this->assertEquals(5, $result['summary']['active_project']);
        $this->assertEquals(20, $result['summary']['total_ahsp']);
        $this->assertEquals(30, $result['summary']['total_tos']);

        $this->assertArrayHasKey('rab_per_year', $result);
        $this->assertEquals(collect([2026 => 5000000]), $result['rab_per_year']);

        $this->assertArrayHasKey('latest_projects', $result);
        $this->assertEquals(collect(['Project A', 'Project B']), $result['latest_projects']);

        $this->assertArrayHasKey('top_categories', $result);
        $this->assertEquals(collect(['Tukang', 'Mandor']), $result['top_categories']);
    }
}

