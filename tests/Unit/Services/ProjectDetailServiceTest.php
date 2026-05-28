<?php

namespace Tests\Unit\Services;

use App\Models\Project;
use App\Modules\Project\Repositories\ProgressRepository;
use App\Modules\Project\Repositories\ProjectExpenditureRepository;
use App\Modules\Project\Services\ProjectDetailService;
use App\Modules\Rab\Services\RabService;
use Mockery;
use Tests\TestCase;

class ProjectDetailServiceTest extends TestCase
{
    protected $progressRepository;
    protected $expenditureRepository;
    protected $rabService;
    protected $projectDetailService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->progressRepository = Mockery::mock(ProgressRepository::class);
        $this->expenditureRepository = Mockery::mock(ProjectExpenditureRepository::class);
        $this->rabService = Mockery::mock(RabService::class);

        $this->projectDetailService = new ProjectDetailService(
            $this->progressRepository,
            $this->expenditureRepository,
            $this->rabService
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test pengambilan data data.
     *
     * Skenario:
     * - Method pemanggilan data dieksekusi dengan parameter yang dibutuhkan.
     * - Repository mencari dan mengembalikan sekumpulan data.
     *
     * Ekspektasi:
     * - Service mengembalikan data data secara utuh yang diharapkan.
     */
    public function test_get_detail_returns_aggregated_data()
    {
        $projectId = 'proj-1';
        $mockProject = Mockery::mock(Project::class);
        
        $mockProjectProgresses = collect([]);
        $mockExpenditures = collect([]);

        $mockProject->shouldReceive('getAttribute')->with('projectProgresses')->andReturn($mockProjectProgresses);
        $mockProject->shouldReceive('getAttribute')->with('projectExpenditures')->andReturn($mockExpenditures);

        $this->progressRepository
            ->shouldReceive('getProjectDetail')
            ->once()
            ->with($projectId)
            ->andReturn($mockProject);

        $this->progressRepository
            ->shouldReceive('getTotalProgress')
            ->once()
            ->with($projectId)
            ->andReturn(45);

        $this->expenditureRepository
            ->shouldReceive('getTotalRealization')
            ->once()
            ->with($projectId)
            ->andReturn(5000000);

        $this->rabService
            ->shouldReceive('generate')
            ->once()
            ->with($projectId)
            ->andReturn(['summary' => ['grand_total' => 20000000]]);

        $result = $this->projectDetailService->getDetail($projectId);

        $this->assertEquals($mockProject, $result['project']);
        $this->assertEquals($mockProjectProgresses, $result['projectProgresses']);
        $this->assertEquals(45, $result['totalProgress']);
        $this->assertEquals($mockExpenditures, $result['expenditures']);
        $this->assertEquals(20000000, $result['totalBudget']);
        $this->assertEquals(5000000, $result['totalRealization']);
        $this->assertEquals(15000000, $result['remainingBudget']);
        $this->assertEquals(25.0, $result['percentageBudget']);
    }
}

