<?php

namespace Tests\Unit\Services;

use App\Models\Ahsp;
use App\Modules\Ahsp\Repositories\AhspRepository;
use App\Modules\Ahsp\Services\AhspService;
use App\Services\CodeGenerators\DotCodeGenerator;
use DomainException;
use Illuminate\Support\Facades\DB;
use Mockery;
use Tests\TestCase;

class AhspServiceTest extends TestCase
{
    protected $ahspRepository;
    protected $codeGenerator;
    protected $ahspService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->ahspRepository = Mockery::mock(AhspRepository::class);
        $this->codeGenerator = Mockery::mock(DotCodeGenerator::class);

        $this->ahspService = new AhspService(
            $this->ahspRepository,
            $this->codeGenerator
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test pengambilan data AHSP menggunakan pagination.
     *
     * Skenario:
     * - Parameter pencarian (search) dan jumlah per halaman (perPage) diberikan.
     * - Repository memanggil method getPaginated.
     *
     * Ekspektasi:
     * - Service mengembalikan hasil pagination dari repository.
     */
    public function test_get_ahsp_returns_paginated_data()
    {
        $search = 'Beton';
        $perPage = 10;
        $expectedResult = ['data' => [], 'total' => 0];

        $this->ahspRepository
            ->shouldReceive('getPaginated')
            ->once()
            ->with($search, $perPage)
            ->andReturn($expectedResult);

        $result = $this->ahspService->getAhsp($search, $perPage);

        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Test pengambilan data AHSP berdasarkan kategori.
     *
     * Skenario:
     * - ID kategori diberikan.
     * - Repository memanggil method getByCategory.
     *
     * Ekspektasi:
     * - Service mengembalikan data AHSP yang sesuai dengan kategori.
     */
    public function test_get_by_category_returns_data()
    {
        $categoryId = 'cat-1';
        $expectedResult = collect([]);

        $this->ahspRepository
            ->shouldReceive('getByCategory')
            ->once()
            ->with($categoryId)
            ->andReturn($expectedResult);

        $result = $this->ahspService->getByCategory($categoryId);

        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Test pembuatan AHSP gagal karena nama pekerjaan sudah ada.
     *
     * Skenario:
     * - Data pembuatan AHSP memiliki nama pekerjaan yang duplikat.
     * - Repository mendeteksi nama tersebut sudah digunakan.
     *
     * Ekspektasi:
     * - DomainException dilempar dengan pesan error yang sesuai.
     */
    public function test_create_ahsp_throws_exception_if_name_exists()
    {
        $data = ['work_name' => 'Pekerjaan Tanah'];

        $this->ahspRepository
            ->shouldReceive('existsByName')
            ->once()
            ->with($data['work_name'])
            ->andReturn(true);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Nama pekerjaan sudah ada.');

        $this->ahspService->createAhsp($data);
    }

    /**
     * Test pembuatan data AHSP baru berhasil.
     *
     * Skenario:
     * - Nama pekerjaan belum ada (tidak duplikat).
     * - Database transaction dijalankan.
     * - Code generator menghasilkan kode AHSP unik.
     * - Repository membuat data AHSP baru.
     *
     * Ekspektasi:
     * - AHSP berhasil dibuat dan direturn oleh service.
     */
    public function test_create_ahsp_successfully()
    {
        $data = ['work_name' => 'Pekerjaan Tanah'];
        $generatedCode = 'A.1.1';
        $ahspMock = Mockery::mock(Ahsp::class);

        DB::shouldReceive('transaction')
            ->once()
            ->andReturnUsing(function ($callback) {
                return $callback();
            });

        $this->ahspRepository
            ->shouldReceive('existsByName')
            ->once()
            ->with($data['work_name'])
            ->andReturn(false);

        $this->codeGenerator
            ->shouldReceive('generate')
            ->once()
            ->with(Ahsp::class, 'work_code', 'A')
            ->andReturn($generatedCode);

        $this->ahspRepository
            ->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($arg) use ($generatedCode) {
                return isset($arg['work_code']) && $arg['work_code'] === $generatedCode;
            }))
            ->andReturn($ahspMock);

        $result = $this->ahspService->createAhsp($data);

        $this->assertEquals($ahspMock, $result);
    }

    /**
     * Test pembaruan AHSP gagal karena nama pekerjaan duplikat.
     *
     * Skenario:
     * - ID AHSP dan data nama pekerjaan baru diberikan.
     * - Repository mendeteksi bahwa nama tersebut sudah digunakan oleh AHSP lain.
     *
     * Ekspektasi:
     * - DomainException dilempar untuk mencegah duplikasi data.
     */
    public function test_update_ahsp_throws_exception_if_name_duplicate()
    {
        $id = '550e8400-e29b-41d4-a716-446655440001';
        $data = ['work_name' => 'Pekerjaan Beton'];
        $ahspMock = Mockery::mock(Ahsp::class);

        $this->ahspRepository
            ->shouldReceive('find')
            ->once()
            ->with($id)
            ->andReturn($ahspMock);

        $this->ahspRepository
            ->shouldReceive('existsByNameExcept')
            ->once()
            ->with($id, $data['work_name'])
            ->andReturn(true);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Nama pekerjaan sudah ada.');

        $this->ahspService->updateAhsp($id, $data);
    }

    /**
     * Test pembaruan data AHSP berhasil.
     *
     * Skenario:
     * - ID AHSP ditemukan dan nama pekerjaan baru valid (tidak duplikat).
     * - Repository melakukan proses update pada model AHSP.
     *
     * Ekspektasi:
     * - Method update direturn dengan status sukses (true).
     */
    public function test_update_ahsp_successfully()
    {
        $id = '550e8400-e29b-41d4-a716-446655440001';
        $data = ['work_name' => 'Pekerjaan Beton'];
        $ahspMock = Mockery::mock(Ahsp::class);

        $this->ahspRepository
            ->shouldReceive('find')
            ->once()
            ->with($id)
            ->andReturn($ahspMock);

        $this->ahspRepository
            ->shouldReceive('existsByNameExcept')
            ->once()
            ->with($id, $data['work_name'])
            ->andReturn(false);

        $ahspMock->shouldReceive('update')
            ->once()
            ->with($data)
            ->andReturn(true);

        $this->ahspService->updateAhsp($id, $data);

        $this->assertTrue(true);
    }

    /**
     * Test penghapusan data AHSP berhasil.
     *
     * Skenario:
     * - ID AHSP yang akan dihapus ditemukan oleh repository.
     * - Model AHSP memanggil method delete.
     *
     * Ekspektasi:
     * - Data AHSP berhasil dihapus dari sistem.
     */
    public function test_delete_ahsp_successfully()
    {
        $id = '550e8400-e29b-41d4-a716-446655440001';
        $ahspMock = Mockery::mock(Ahsp::class);

        $this->ahspRepository
            ->shouldReceive('find')
            ->once()
            ->with($id)
            ->andReturn($ahspMock);

        $ahspMock->shouldReceive('delete')
            ->once()
            ->andReturn(true);

        $this->ahspService->deleteAhsp($id);

        $this->assertTrue(true);
    }
}

