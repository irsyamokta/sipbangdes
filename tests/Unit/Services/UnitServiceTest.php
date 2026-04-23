<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Mockery;
use DomainException;

use Illuminate\Support\Facades\DB;

use App\Models\MasterUnit;
use App\Modules\Unit\Services\UnitService;
use App\Modules\Unit\Repositories\UnitRepository;
use App\Services\CodeGeneratorService;

class UnitServiceTest extends TestCase
{
    protected $unitRepository;
    protected $codeGenerator;
    protected $unitService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->unitRepository = Mockery::mock(UnitRepository::class);
        $this->codeGenerator = Mockery::mock(CodeGeneratorService::class);

        $this->unitService = new UnitService(
            $this->unitRepository,
            $this->codeGenerator
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_units_with_pagination()
    {
        $search = 'meter';
        $perPage = 10;

        $this->unitRepository
            ->shouldReceive('getPaginated')
            ->once()
            ->with($search, $perPage)
            ->andReturn('paginated_result');

        $result = $this->unitService->getUnits(
            $search,
            true,
            $perPage
        );

        $this->assertEquals('paginated_result', $result);
    }

    public function test_get_units_without_pagination()
    {
        $search = 'meter';

        $this->unitRepository
            ->shouldReceive('getAll')
            ->once()
            ->with($search)
            ->andReturn('all_result');

        $result = $this->unitService->getUnits(
            $search,
            false
        );

        $this->assertEquals('all_result', $result);
    }

    public function test_create_unit_throw_exception_if_name_exists()
    {
        $data = [
            'name' => 'm'
        ];

        $this->unitRepository
            ->shouldReceive('existsByName')
            ->once()
            ->with('m')
            ->andReturn(true);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Nama satuan sudah ada.');

        $this->unitService->createUnit($data);
    }

    public function test_create_unit_successfully()
    {
        $data = [
            'name' => 'm',
            'category' => 'Panjang'
        ];

        DB::shouldReceive('transaction')
            ->once()
            ->andReturnUsing(function ($callback) {
                return $callback();
            });

        $this->unitRepository
            ->shouldReceive('existsByName')
            ->once()
            ->with('m')
            ->andReturn(false);

        $this->codeGenerator
            ->shouldReceive('generate')
            ->once()
            ->with(
                MasterUnit::class,
                'code',
                'SAT'
            )
            ->andReturn('SAT001');

        $unitMock = Mockery::mock(MasterUnit::class);

        $this->unitRepository
            ->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($arg) {
                return isset($arg['code'])
                    && $arg['code'] === 'SAT001';
            }))
            ->andReturn($unitMock);

        $result = $this->unitService->createUnit($data);

        $this->assertEquals($unitMock, $result);
    }

    public function test_update_unit_throw_exception_if_name_duplicate()
    {
        $unitId = '550e8400-e29b-41d4-a716-446655440005';

        $data = [
            'name' => 'm'
        ];

        $unitMock = Mockery::mock(MasterUnit::class);

        $this->unitRepository
            ->shouldReceive('find')
            ->once()
            ->with($unitId)
            ->andReturn($unitMock);

        $this->unitRepository
            ->shouldReceive('existsByNameExcept')
            ->once()
            ->with($unitId, 'm')
            ->andReturn(true);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Nama satuan sudah ada.');

        $this->unitService->updateUnit($unitId, $data);
    }

    public function test_update_unit_successfully()
    {
        $unitId = '550e8400-e29b-41d4-a716-446655440004';

        $data = [
            'name' => 'kg',
            'category' => 'Berat'
        ];

        $unitMock = Mockery::mock(MasterUnit::class);

        $this->unitRepository
            ->shouldReceive('find')
            ->once()
            ->with($unitId)
            ->andReturn($unitMock);

        $this->unitRepository
            ->shouldReceive('existsByNameExcept')
            ->once()
            ->with($unitId, 'kg')
            ->andReturn(false);

        $this->unitRepository
            ->shouldReceive('update')
            ->once()
            ->with($unitMock, $data)
            ->andReturn(true);

        $result = $this->unitService->updateUnit($unitId, $data);

        $this->assertTrue($result);
    }

    public function test_delete_unit_successfully()
    {
        $unitId = '550e8400-e29b-41d4-a716-446655440006';

        $unit = new MasterUnit();

        $this->unitRepository
            ->shouldReceive('find')
            ->once()
            ->with($unitId)
            ->andReturn($unit);

        $this->unitRepository
            ->shouldReceive('delete')
            ->once()
            ->with($unit)
            ->andReturn(true);

        $result = $this->unitService->deleteUnit($unitId);

        $this->assertTrue($result);
    }
}