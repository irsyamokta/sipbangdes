<?php

namespace Tests\Unit\Services;

use App\Models\WorkerCategory;
use App\Modules\WorkerCategory\Repositories\WorkerCategoryRepository;
use App\Modules\WorkerCategory\Services\WorkerCategoryService;
use DomainException;
use Mockery;
use Tests\TestCase;

class WorkerCategoryServiceTest extends TestCase
{
    protected $workerCategoryRepository;
    protected $workerCategoryService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->workerCategoryRepository = Mockery::mock(WorkerCategoryRepository::class);

        $this->workerCategoryService = new WorkerCategoryService(
            $this->workerCategoryRepository
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test pengambilan data kategori pekerja.
     *
     * Skenario:
     * - Method pemanggilan data dieksekusi dengan parameter yang dibutuhkan.
     * - Repository mencari dan mengembalikan sekumpulan data.
     *
     * Ekspektasi:
     * - Service mengembalikan data kategori pekerja secara utuh yang diharapkan.
     */
    public function test_get_worker_category_returns_paginated_data()
    {
        $search = 'Tukang';
        $perPage = 10;
        $expectedResult = ['data' => [], 'total' => 0];

        $this->workerCategoryRepository
            ->shouldReceive('getPaginated')
            ->once()
            ->with($search, $perPage)
            ->andReturn($expectedResult);

        $result = $this->workerCategoryService->getWorkerCategory($search, $perPage);

        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Test pembuatan kategori pekerja gagal karena kondisi tidak terpenuhi.
     *
     * Skenario:
     * - Data pembuatan kategori pekerja dikirimkan namun melanggar validasi bisnis (misal: duplikat nama/kode).
     * - Repository mendeteksi pelanggaran tersebut.
     *
     * Ekspektasi:
     * - DomainException dilempar dengan pesan error yang relevan untuk mencegah duplikasi.
     */
    public function test_create_worker_category_throws_exception_if_name_exists()
    {
        $data = ['name' => 'Tukang Batu'];

        $this->workerCategoryRepository
            ->shouldReceive('existsByName')
            ->once()
            ->with($data['name'])
            ->andReturn(true);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Kategori pekerjaan sudah ada.');

        $this->workerCategoryService->createWorkerCategory($data);
    }

    /**
     * Test pembuatan data kategori pekerja baru berhasil.
     *
     * Skenario:
     * - Data yang diberikan lolos semua validasi bisnis.
     * - Database transaction memastikan konsistensi.
     * - Repository memproses penyimpanan data ke tabel terkait.
     *
     * Ekspektasi:
     * - Data kategori pekerja berhasil dibuat dan model baru dikembalikan oleh service.
     */
    public function test_create_worker_category_successfully()
    {
        $data = ['name' => 'Tukang Batu'];
        $workerCategoryMock = Mockery::mock(WorkerCategory::class);

        $this->workerCategoryRepository
            ->shouldReceive('existsByName')
            ->once()
            ->with($data['name'])
            ->andReturn(false);

        $this->workerCategoryRepository
            ->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn($workerCategoryMock);

        $result = $this->workerCategoryService->createWorkerCategory($data);

        $this->assertEquals($workerCategoryMock, $result);
    }

    /**
     * Test pembaruan kategori pekerja gagal karena kondisi tidak terpenuhi.
     *
     * Skenario:
     * - ID dan data pembaruan dikirimkan.
     * - Terjadi konflik bisnis, seperti penggunaan nama yang sudah dipakai data lain.
     * - Repository menolak eksekusi karena pengecekan eksistensi.
     *
     * Ekspektasi:
     * - DomainException dilempar untuk mencegah penyimpanan data tidak konsisten.
     */
    public function test_update_worker_category_throws_exception_if_name_duplicate()
    {
        $id = '550e8400-e29b-41d4-a716-446655440001';
        $data = ['name' => 'Tukang Kayu'];
        $workerCategoryMock = Mockery::mock(WorkerCategory::class);

        $this->workerCategoryRepository
            ->shouldReceive('find')
            ->once()
            ->with($id)
            ->andReturn($workerCategoryMock);

        $this->workerCategoryRepository
            ->shouldReceive('existsByNameExcept')
            ->once()
            ->with($id, $data['name'])
            ->andReturn(true);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Kategori pekerjaan sudah ada.');

        $this->workerCategoryService->updateWorkerCategory($id, $data);
    }

    /**
     * Test pembaruan data kategori pekerja berhasil.
     *
     * Skenario:
     * - ID valid dan data baru yang dikirimkan memenuhi syarat.
     * - Repository menemukan model terkait dan menerapkan perubahan.
     * - Data berhasil tersimpan.
     *
     * Ekspektasi:
     * - Method mengembalikan status sukses (true) yang menandakan keberhasilan update.
     */
    public function test_update_worker_category_successfully()
    {
        $id = '550e8400-e29b-41d4-a716-446655440001';
        $data = ['name' => 'Tukang Kayu'];
        $workerCategoryMock = Mockery::mock(WorkerCategory::class);

        $this->workerCategoryRepository
            ->shouldReceive('find')
            ->once()
            ->with($id)
            ->andReturn($workerCategoryMock);

        $this->workerCategoryRepository
            ->shouldReceive('existsByNameExcept')
            ->once()
            ->with($id, $data['name'])
            ->andReturn(false);

        $this->workerCategoryRepository
            ->shouldReceive('update')
            ->once()
            ->with($workerCategoryMock, $data)
            ->andReturn(true);

        $result = $this->workerCategoryService->updateWorkerCategory($id, $data);

        $this->assertTrue($result);
    }

    /**
     * Test penghapusan data kategori pekerja berhasil.
     *
     * Skenario:
     * - ID kategori pekerja valid dan tidak melanggar aturan foreign key atau batasan hapus.
     * - Repository mengeksekusi penghapusan dari sumber data.
     *
     * Ekspektasi:
     * - Data kategori pekerja berhasil dihapus sepenuhnya (mengembalikan true).
     */
    public function test_delete_worker_category_successfully()
    {
        $id = '550e8400-e29b-41d4-a716-446655440001';
        $workerCategoryMock = Mockery::mock(WorkerCategory::class);

        $this->workerCategoryRepository
            ->shouldReceive('find')
            ->once()
            ->with($id)
            ->andReturn($workerCategoryMock);

        $this->workerCategoryRepository
            ->shouldReceive('delete')
            ->once()
            ->with($workerCategoryMock)
            ->andReturn(true);

        $result = $this->workerCategoryService->deleteWorkerCategory($id);

        $this->assertTrue($result);
    }
}

