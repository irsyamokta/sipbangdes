<?php

namespace Tests\Unit\Services;

use App\Models\OperationalCost;
use App\Modules\Rab\Repositories\OperationalCostRepository;
use App\Modules\Rab\Services\OperationalCostService;
use DomainException;
use Mockery;
use Tests\TestCase;

class OperationalCostServiceTest extends TestCase
{
    protected $operationalCostRepository;
    protected $operationalCostService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->operationalCostRepository = Mockery::mock(OperationalCostRepository::class);

        $this->operationalCostService = new OperationalCostService(
            $this->operationalCostRepository
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test update throws exception if project is approved.
     *
     * The update() method fetches the OperationalCost via repository, then accesses
     * $operationalCost->project directly. We can mock the OperationalCost model and
     * stub the project relation to return an object with rab_status = 'approved'.
     */
    public function test_update_throws_exception_if_project_approved()
    {
        $id = '550e8400-e29b-41d4-a716-446655440001';
        $data = ['volume' => 2, 'unit_price' => 50000];

        $mockProject = new \stdClass();
        $mockProject->rab_status = 'approved';

        $mockCost = Mockery::mock(OperationalCost::class)->makePartial();
        $mockCost->project = $mockProject;

        $this->operationalCostRepository
            ->shouldReceive('find')
            ->once()
            ->with($id)
            ->andReturn($mockCost);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('RAB sudah disetujui, tidak bisa memodifikasi data.');

        $this->operationalCostService->update($id, $data);
    }

    /**
     * Test update successfully when project is not approved.
     */
    public function test_update_successfully()
    {
        $id = '550e8400-e29b-41d4-a716-446655440001';
        $data = ['volume' => 3, 'unit_price' => 75000];

        $mockProject = new \stdClass();
        $mockProject->rab_status = 'draft';

        $mockCost = Mockery::mock(OperationalCost::class)->makePartial();
        $mockCost->project = $mockProject;

        $this->operationalCostRepository
            ->shouldReceive('find')
            ->once()
            ->with($id)
            ->andReturn($mockCost);

        $mockCost->shouldReceive('update')
            ->once()
            ->with(Mockery::on(function ($arg) {
                return $arg['total'] === 3 * 75000;
            }))
            ->andReturn(true);

        $result = $this->operationalCostService->update($id, $data);

        $this->assertTrue($result);
    }

    /**
     * Test destroy throws exception if project is approved.
     */
    public function test_destroy_throws_exception_if_project_approved()
    {
        $id = '550e8400-e29b-41d4-a716-446655440001';

        $mockProject = new \stdClass();
        $mockProject->rab_status = 'approved';

        $mockCost = Mockery::mock(OperationalCost::class)->makePartial();
        $mockCost->project = $mockProject;

        $this->operationalCostRepository
            ->shouldReceive('find')
            ->once()
            ->with($id)
            ->andReturn($mockCost);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('RAB sudah disetujui, tidak bisa memodifikasi data.');

        $this->operationalCostService->destroy($id);
    }

    /**
     * Test destroy successfully when project is not approved.
     */
    public function test_destroy_successfully()
    {
        $id = '550e8400-e29b-41d4-a716-446655440001';

        $mockProject = new \stdClass();
        $mockProject->rab_status = 'draft';

        $mockCost = Mockery::mock(OperationalCost::class)->makePartial();
        $mockCost->project = $mockProject;

        $this->operationalCostRepository
            ->shouldReceive('find')
            ->once()
            ->with($id)
            ->andReturn($mockCost);

        $mockCost->shouldReceive('delete')
            ->once()
            ->andReturn(true);

        $result = $this->operationalCostService->destroy($id);

        $this->assertTrue($result);
    }
}

