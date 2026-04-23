<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Mockery;

use Illuminate\Support\Facades\DB;

use App\Models\MasterMaterial;
use App\Modules\Material\Services\MaterialService;
use App\Modules\Material\Repositories\MaterialRepository;
use App\Services\CodeGeneratorService;

class MaterialServiceTest extends TestCase
{
    protected $materialRepository;
    protected $codeGenerator;
    protected $materialService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->materialRepository =
            Mockery::mock(MaterialRepository::class);

        $this->codeGenerator =
            Mockery::mock(CodeGeneratorService::class);

        $this->materialService = new MaterialService(
            $this->materialRepository,
            $this->codeGenerator
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_materials_with_pagination()
    {
        $search = 'semen';
        $perPage = 10;

        $this->materialRepository
            ->shouldReceive('getPaginated')
            ->once()
            ->with($search, $perPage)
            ->andReturn('paginated_result');

        $result = $this->materialService->getMaterials(
            $search,
            true,
            $perPage
        );

        $this->assertEquals(
            'paginated_result',
            $result
        );
    }

    public function test_get_materials_without_pagination()
    {
        $search = 'semen';

        $this->materialRepository
            ->shouldReceive('getAll')
            ->once()
            ->with($search)
            ->andReturn('all_result');

        $result = $this->materialService->getMaterials(
            $search,
            false
        );

        $this->assertEquals(
            'all_result',
            $result
        );
    }

    public function test_create_material_successfully()
    {
        $data = [
            'name' => 'Semen',
            'unit' => 'kg',
            'price' => 75000
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
                MasterMaterial::class,
                'code',
                'MAT'
            )
            ->andReturn('MAT001');

        $materialMock =
            Mockery::mock(MasterMaterial::class);

        $this->materialRepository
            ->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($arg) {
                return isset($arg['code'])
                    && $arg['code'] === 'MAT001';
            }))
            ->andReturn($materialMock);

        $result = $this->materialService
            ->createMaterial($data);

        $this->assertEquals(
            $materialMock,
            $result
        );
    }

    public function test_update_material_successfully()
    {
        $materialId = '550e8400-e29b-41d4-a716-446655440010';

        $data = [
            'name' => 'Pasir',
            'unit' => 'm3',
            'price' => 55000
        ];

        $materialMock =
            Mockery::mock(MasterMaterial::class);

        $this->materialRepository
            ->shouldReceive('find')
            ->once()
            ->with($materialId)
            ->andReturn($materialMock);

        $this->materialRepository
            ->shouldReceive('update')
            ->once()
            ->with($materialMock, $data)
            ->andReturn(true);

        $result = $this->materialService
            ->updateMaterial($materialId, $data);

        $this->assertTrue($result);
    }

    public function test_delete_material_successfully()
    {
        $materialId = '550e8400-e29b-41d4-a716-446655440011';

        $material = new MasterMaterial();

        $this->materialRepository
            ->shouldReceive('find')
            ->once()
            ->with($materialId)
            ->andReturn($material);

        $this->materialRepository
            ->shouldReceive('delete')
            ->once()
            ->with($material)
            ->andReturn(true);

        $result = $this->materialService
            ->deleteMaterial($materialId);

        $this->assertTrue($result);
    }
}