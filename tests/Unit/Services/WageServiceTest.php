<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Mockery;

use Illuminate\Support\Facades\DB;

use App\Models\MasterWage;
use App\Modules\Wage\Services\WageService;
use App\Modules\Wage\Repositories\WageRepository;
use App\Services\CodeGeneratorService;

class WageServiceTest extends TestCase
{
    protected $wageRepository;
    protected $codeGenerator;
    protected $wageService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->wageRepository =
            Mockery::mock(WageRepository::class);

        $this->codeGenerator =
            Mockery::mock(CodeGeneratorService::class);

        $this->wageService = new WageService(
            $this->wageRepository,
            $this->codeGenerator
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_wages_with_pagination()
    {
        $search = 'tukang';
        $perPage = 10;

        $this->wageRepository
            ->shouldReceive('getPaginated')
            ->once()
            ->with($search, $perPage)
            ->andReturn('paginated_result');

        $result = $this->wageService->getWages(
            $search,
            true,
            $perPage
        );

        $this->assertEquals(
            'paginated_result',
            $result
        );
    }

    public function test_get_wages_without_pagination()
    {
        $search = 'tukang';

        $this->wageRepository
            ->shouldReceive('getAll')
            ->once()
            ->with($search)
            ->andReturn('all_result');

        $result = $this->wageService->getWages(
            $search,
            false
        );

        $this->assertEquals(
            'all_result',
            $result
        );
    }

    public function test_create_wage_successfully()
    {
        $data = [
            'name' => 'Tukang Batu',
            'unit' => 'OH',
            'price' => 125000
        ];

        DB::shouldReceive('transaction')
            ->once()
            ->andReturnUsing(function ($callback) {
                return $callback();
            });

        $this->codeGenerator
            ->shouldReceive('generate')
            ->once()
            ->with(
                MasterWage::class,
                'code',
                'UPH'
            )
            ->andReturn('UPH001');

        $wageMock =
            Mockery::mock(MasterWage::class);

        $this->wageRepository
            ->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($arg) {
                return isset($arg['code'])
                    && $arg['code'] === 'UPH001';
            }))
            ->andReturn($wageMock);

        $result = $this->wageService
            ->createWage($data);

        $this->assertEquals(
            $wageMock,
            $result
        );
    }

    public function test_update_wage_successfully()
    {
        $wageId = '550e8400-e29b-41d4-a716-446655440030';

        $data = [
            'name' => 'Mandor',
            'unit' => 'OH',
            'price' => 135000
        ];

        $wageMock =
            Mockery::mock(MasterWage::class);

        $this->wageRepository
            ->shouldReceive('find')
            ->once()
            ->with($wageId)
            ->andReturn($wageMock);

        $this->wageRepository
            ->shouldReceive('update')
            ->once()
            ->with($wageMock, $data)
            ->andReturn(true);

        $result = $this->wageService
            ->updateWage($wageId, $data);

        $this->assertTrue($result);
    }

    public function test_delete_wage_successfully()
    {
        $wageId = '550e8400-e29b-41d4-a716-446655440031';

        $wage = new MasterWage();

        $this->wageRepository
            ->shouldReceive('find')
            ->once()
            ->with($wageId)
            ->andReturn($wage);

        $this->wageRepository
            ->shouldReceive('delete')
            ->once()
            ->with($wage)
            ->andReturn(true);

        $result = $this->wageService
            ->deleteWage($wageId);

        $this->assertTrue($result);
    }
}