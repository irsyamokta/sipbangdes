<?php

namespace Tests\Unit\Services;

use App\Models\WorkerItem;
use App\Modules\WorkerCategory\Repositories\WorkerItemRepository;
use App\Modules\WorkerCategory\Services\WorkerItemService;
use DomainException;
use Mockery;
use Tests\TestCase;

class WorkerItemServiceTest extends TestCase
{
    protected $workerItemRepository;
    protected $workerItemService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->workerItemRepository = Mockery::mock(WorkerItemRepository::class);

        $this->workerItemService = new WorkerItemService(
            $this->workerItemRepository
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test pengambilan data item pekerja.
     *
     * Skenario:
     * - Method pemanggilan data dieksekusi dengan parameter yang dibutuhkan.
     * - Repository mencari dan mengembalikan sekumpulan data.
     *
     * Ekspektasi:
     * - Service mengembalikan data item pekerja secara utuh yang diharapkan.
     */
    public function test_get_worker_items_returns_items()
    {
        $categoryId = '550e8400-e29b-41d4-a716-446655440001';
        $expectedResult = collect([]);

        $this->workerItemRepository
            ->shouldReceive('getWorkerItems')
            ->once()
            ->with($categoryId)
            ->andReturn($expectedResult);

        $result = $this->workerItemService->getWorkerItems($categoryId);

        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Test pembuatan item pekerja gagal karena kondisi tidak terpenuhi.
     *
     * Skenario:
     * - Data pembuatan item pekerja dikirimkan namun melanggar validasi bisnis (misal: duplikat nama/kode).
     * - Repository mendeteksi pelanggaran tersebut.
     *
     * Ekspektasi:
     * - DomainException dilempar dengan pesan error yang relevan untuk mencegah duplikasi.
     */
    public function test_create_worker_item_throws_exception_if_name_exists()
    {
        $data = [
            'work_name' => 'Pemasangan Batu Bata',
            'category_id' => 1
        ];

        $this->workerItemRepository
            ->shouldReceive('existsByName')
            ->once()
            ->with($data['work_name'], $data['category_id'])
            ->andReturn(true);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('AHSP sudah ada dalam kategori ini.');

        $this->workerItemService->createWorkerItem($data);
    }

    /**
     * Test pembuatan data item pekerja baru berhasil.
     *
     * Skenario:
     * - Data yang diberikan lolos semua validasi bisnis.
     * - Database transaction memastikan konsistensi.
     * - Repository memproses penyimpanan data ke tabel terkait.
     *
     * Ekspektasi:
     * - Data item pekerja berhasil dibuat dan model baru dikembalikan oleh service.
     */
    public function test_create_worker_item_successfully()
    {
        $data = [
            'work_name' => 'Pemasangan Batu Bata',
            'category_id' => 1
        ];
        $workerItemMock = Mockery::mock(WorkerItem::class);

        $this->workerItemRepository
            ->shouldReceive('existsByName')
            ->once()
            ->with($data['work_name'], $data['category_id'])
            ->andReturn(false);

        $this->workerItemRepository
            ->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn($workerItemMock);

        $result = $this->workerItemService->createWorkerItem($data);

        $this->assertEquals($workerItemMock, $result);
    }

    /**
     * Test pembaruan item pekerja gagal karena kondisi tidak terpenuhi.
     *
     * Skenario:
     * - ID dan data pembaruan dikirimkan.
     * - Terjadi konflik bisnis, seperti penggunaan nama yang sudah dipakai data lain.
     * - Repository menolak eksekusi karena pengecekan eksistensi.
     *
     * Ekspektasi:
     * - DomainException dilempar untuk mencegah penyimpanan data tidak konsisten.
     */
    public function test_update_worker_item_throws_exception_if_name_duplicate()
    {
        $id = '550e8400-e29b-41d4-a716-446655440001';
        $data = [
            'work_name' => 'Pemasangan Genteng',
            'category_id' => 1
        ];
        $workerItemMock = Mockery::mock(WorkerItem::class);

        $this->workerItemRepository
            ->shouldReceive('find')
            ->once()
            ->with($id)
            ->andReturn($workerItemMock);

        $this->workerItemRepository
            ->shouldReceive('existsByNameExcept')
            ->once()
            ->with($id, $data['work_name'], $data['category_id'])
            ->andReturn(true);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('AHSP sudah ada dalam kategori ini.');

        $this->workerItemService->updateWorkerItem($id, $data);
    }

    /**
     * Test pembaruan data item pekerja berhasil.
     *
     * Skenario:
     * - ID valid dan data baru yang dikirimkan memenuhi syarat.
     * - Repository menemukan model terkait dan menerapkan perubahan.
     * - Data berhasil tersimpan.
     *
     * Ekspektasi:
     * - Method mengembalikan status sukses (true) yang menandakan keberhasilan update.
     */
    public function test_update_worker_item_successfully()
    {
        $id = '550e8400-e29b-41d4-a716-446655440001';
        $data = [
            'work_name' => 'Pemasangan Genteng',
            'category_id' => 1
        ];
        $workerItemMock = Mockery::mock(WorkerItem::class);

        $this->workerItemRepository
            ->shouldReceive('find')
            ->once()
            ->with($id)
            ->andReturn($workerItemMock);

        $this->workerItemRepository
            ->shouldReceive('existsByNameExcept')
            ->once()
            ->with($id, $data['work_name'], $data['category_id'])
            ->andReturn(false);

        $this->workerItemRepository
            ->shouldReceive('update')
            ->once()
            ->with($workerItemMock, $data)
            ->andReturn(true);

        $result = $this->workerItemService->updateWorkerItem($id, $data);

        $this->assertTrue($result);
    }

    /**
     * Test penghapusan data item pekerja berhasil.
     *
     * Skenario:
     * - ID item pekerja valid dan tidak melanggar aturan foreign key atau batasan hapus.
     * - Repository mengeksekusi penghapusan dari sumber data.
     *
     * Ekspektasi:
     * - Data item pekerja berhasil dihapus sepenuhnya (mengembalikan true).
     */
    public function test_delete_worker_item_successfully()
    {
        $id = '550e8400-e29b-41d4-a716-446655440001';
        $workerItemMock = Mockery::mock(WorkerItem::class);

        $this->workerItemRepository
            ->shouldReceive('find')
            ->once()
            ->with($id)
            ->andReturn($workerItemMock);

        $this->workerItemRepository
            ->shouldReceive('delete')
            ->once()
            ->with($workerItemMock)
            ->andReturn(true);

        $result = $this->workerItemService->deleteWorkerItem($id);

        $this->assertTrue($result);
    }
}

