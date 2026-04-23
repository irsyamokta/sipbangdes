<?php

namespace Tests\Unit\Services;

use App\Contracts\CodeGeneratorInterface;
use App\Models\MasterMaterial;
use App\Modules\Material\Repositories\MaterialRepository;
use App\Modules\Material\Services\MaterialService;
use Illuminate\Support\Facades\DB;
use Mockery;
use Tests\TestCase;

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
            Mockery::mock(CodeGeneratorInterface::class);

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

    /**
     * Test pengambilan data material menggunakan pagination.
     *
     * Skenario:
     * - Parameter pencarian (search) diberikan.
     * - Pagination diaktifkan dengan jumlah data per halaman tertentu.
     * - Repository memanggil method getPaginated.
     *
     * Ekspektasi:
     * - Service mengembalikan hasil pagination
     *   yang sama dengan hasil dari repository.
     */
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

    /**
     * Test pengambilan seluruh data material tanpa pagination.
     *
     * Skenario:
     * - Parameter pencarian (search) diberikan.
     * - Pagination dinonaktifkan.
     * - Repository memanggil method getAll.
     *
     * Ekspektasi:
     * - Service mengembalikan seluruh data material
     *   sesuai hasil dari repository.
     */
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

    /**
     * Test pembuatan data material baru berhasil.
     *
     * Skenario:
     * - Database transaction dijalankan.
     * - Code generator menghasilkan kode material unik.
     * - Repository membuat data material baru
     *   dengan kode yang telah dihasilkan.
     *
     * Ekspektasi:
     * - Material berhasil dibuat.
     * - Kode material otomatis tersimpan dalam data.
     * - Method createMaterial mengembalikan instance material.
     */
    public function test_create_material_successfully()
    {
        $data = [
            'name' => 'Semen',
            'unit' => 'kg',
            'price' => 75000,
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

    /**
     * Test pembaruan data material berhasil.
     *
     * Skenario:
     * - Material ditemukan berdasarkan ID.
     * - Repository menjalankan proses update
     *   dengan data baru yang diberikan.
     *
     * Ekspektasi:
     * - Method updateMaterial mengembalikan nilai true
     *   sebagai indikasi bahwa proses update berhasil.
     */
    public function test_update_material_successfully()
    {
        $materialId = '550e8400-e29b-41d4-a716-446655440010';

        $data = [
            'name' => 'Pasir',
            'unit' => 'm3',
            'price' => 55000,
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

    /**
     * Test penghapusan data material berhasil.
     *
     * Skenario:
     * - Material ditemukan berdasarkan ID.
     * - Repository menjalankan proses delete
     *   terhadap material tersebut.
     *
     * Ekspektasi:
     * - Method deleteMaterial mengembalikan nilai true
     *   sebagai indikasi bahwa proses penghapusan berhasil.
     */
    public function test_delete_material_successfully()
    {
        $materialId = '550e8400-e29b-41d4-a716-446655440011';

        $material = new MasterMaterial;

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
