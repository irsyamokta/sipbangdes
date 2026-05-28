<?php

namespace Tests\Unit\Services;

use App\Models\TakeOffSheet;
use App\Modules\TakeOffSheet\Repositories\TakeOffSheetRepository;
use App\Modules\TakeOffSheet\Services\TakeOffSheetService;
use DomainException;
use Illuminate\Support\Facades\DB;
use Mockery;
use Tests\TestCase;

class TakeOffSheetServiceTest extends TestCase
{
    protected $takeOffSheetRepository;
    protected $takeOffSheetService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->takeOffSheetRepository = Mockery::mock(TakeOffSheetRepository::class);

        $this->takeOffSheetService = new TakeOffSheetService(
            $this->takeOffSheetRepository
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test pengambilan data Take Off Sheet.
     *
     * Skenario:
     * - Method pemanggilan data dieksekusi dengan parameter yang dibutuhkan.
     * - Repository mencari dan mengembalikan sekumpulan data.
     *
     * Ekspektasi:
     * - Service mengembalikan data Take Off Sheet secara utuh yang diharapkan.
     */
    public function test_get_take_off_sheets_returns_paginated_data()
    {
        $search = 'Galian';
        $projectId = 'proj-1';
        $expectedResult = ['data' => [], 'total' => 0];

        $this->takeOffSheetRepository
            ->shouldReceive('getPaginated')
            ->once()
            ->with($search, $projectId)
            ->andReturn($expectedResult);

        $result = $this->takeOffSheetService->getTakeOffSheets($search, $projectId);

        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Test pembuatan project gagal karena kondisi tidak terpenuhi.
     *
     * Skenario:
     * - Data pembuatan project dikirimkan namun melanggar validasi bisnis (misal: duplikat nama/kode).
     * - Repository mendeteksi pelanggaran tersebut.
     *
     * Ekspektasi:
     * - DomainException dilempar dengan pesan error yang relevan untuk mencegah duplikasi.
     */
    public function test_create_throws_exception_if_project_approved()
    {
        $data = [
            'project_id' => 'proj-1',
            'work_name' => 'Galian Tanah',
            'worker_category_id' => 1
        ];

        DB::shouldReceive('transaction')
            ->once()
            ->andReturnUsing(function ($callback) {
                return $callback();
            });

        $this->takeOffSheetRepository
            ->shouldReceive('getProjectStatus')
            ->once()
            ->with($data['project_id'])
            ->andReturn('approved');

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Proyek sudah disetujui, tidak dapat mengubah data!');

        $this->takeOffSheetService->create($data);
    }

    /**
     * Test pembuatan data gagal karena kondisi tidak terpenuhi.
     *
     * Skenario:
     * - Data pembuatan data dikirimkan namun melanggar validasi bisnis (misal: duplikat nama/kode).
     * - Repository mendeteksi pelanggaran tersebut.
     *
     * Ekspektasi:
     * - DomainException dilempar dengan pesan error yang relevan untuk mencegah duplikasi.
     */
    public function test_create_throws_exception_if_duplicate()
    {
        $data = [
            'project_id' => 'proj-1',
            'work_name' => 'Galian Tanah',
            'worker_category_id' => 1
        ];

        DB::shouldReceive('transaction')
            ->once()
            ->andReturnUsing(function ($callback) {
                return $callback();
            });

        $this->takeOffSheetRepository
            ->shouldReceive('getProjectStatus')
            ->once()
            ->with($data['project_id'])
            ->andReturn('draft');

        $this->takeOffSheetRepository
            ->shouldReceive('existsByNameExcept')
            ->once()
            ->with(null, $data['work_name'], $data['project_id'], $data['worker_category_id'])
            ->andReturn(true);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('AHSP sudah digunakan pada proyek & kategori ini.');

        $this->takeOffSheetService->create($data);
    }

    /**
     * Test pembuatan data data baru berhasil.
     *
     * Skenario:
     * - Data yang diberikan lolos semua validasi bisnis.
     * - Database transaction memastikan konsistensi.
     * - Repository memproses penyimpanan data ke tabel terkait.
     *
     * Ekspektasi:
     * - Data data berhasil dibuat dan model baru dikembalikan oleh service.
     */
    public function test_create_successfully()
    {
        $data = [
            'project_id' => 'proj-1',
            'work_name' => 'Galian Tanah',
            'worker_category_id' => 1
        ];
        $mockModel = new TakeOffSheet();

        DB::shouldReceive('transaction')
            ->once()
            ->andReturnUsing(function ($callback) {
                return $callback();
            });

        $this->takeOffSheetRepository
            ->shouldReceive('getProjectStatus')
            ->once()
            ->with($data['project_id'])
            ->andReturn('draft');

        $this->takeOffSheetRepository
            ->shouldReceive('existsByNameExcept')
            ->once()
            ->with(null, $data['work_name'], $data['project_id'], $data['worker_category_id'])
            ->andReturn(false);

        $this->takeOffSheetRepository
            ->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn($mockModel);

        $result = $this->takeOffSheetService->create($data);

        $this->assertEquals($mockModel, $result);
    }

    /**
     * Test pembaruan project gagal karena kondisi tidak terpenuhi.
     *
     * Skenario:
     * - ID dan data pembaruan dikirimkan.
     * - Terjadi konflik bisnis, seperti penggunaan nama yang sudah dipakai data lain.
     * - Repository menolak eksekusi karena pengecekan eksistensi.
     *
     * Ekspektasi:
     * - DomainException dilempar untuk mencegah penyimpanan data tidak konsisten.
     */
    public function test_update_throws_exception_if_project_approved()
    {
        $id = '550e8400-e29b-41d4-a716-446655440001';
        $data = [
            'project_id' => 'proj-1',
            'work_name' => 'Galian Tanah',
            'worker_category_id' => 1
        ];
        
        $mockModel = new TakeOffSheet();

        $this->takeOffSheetRepository
            ->shouldReceive('find')
            ->once()
            ->with($id)
            ->andReturn($mockModel);

        $this->takeOffSheetRepository
            ->shouldReceive('getProjectStatus')
            ->once()
            ->with($data['project_id'])
            ->andReturn('approved');

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Proyek sudah disetujui, tidak dapat mengubah data!');

        $this->takeOffSheetService->update($id, $data);
    }

    /**
     * Test pembaruan data gagal karena kondisi tidak terpenuhi.
     *
     * Skenario:
     * - ID dan data pembaruan dikirimkan.
     * - Terjadi konflik bisnis, seperti penggunaan nama yang sudah dipakai data lain.
     * - Repository menolak eksekusi karena pengecekan eksistensi.
     *
     * Ekspektasi:
     * - DomainException dilempar untuk mencegah penyimpanan data tidak konsisten.
     */
    public function test_update_throws_exception_if_duplicate()
    {
        $id = '550e8400-e29b-41d4-a716-446655440001';
        $data = [
            'project_id' => 'proj-1',
            'work_name' => 'Galian Tanah',
            'worker_category_id' => 1
        ];
        
        $mockModel = new TakeOffSheet();

        $this->takeOffSheetRepository
            ->shouldReceive('find')
            ->once()
            ->with($id)
            ->andReturn($mockModel);

        $this->takeOffSheetRepository
            ->shouldReceive('getProjectStatus')
            ->once()
            ->with($data['project_id'])
            ->andReturn('draft');

        $this->takeOffSheetRepository
            ->shouldReceive('existsByNameExcept')
            ->once()
            ->with($id, $data['work_name'], $data['project_id'], $data['worker_category_id'])
            ->andReturn(true);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('AHSP sudah digunakan pada proyek & kategori ini.');

        $this->takeOffSheetService->update($id, $data);
    }

    /**
     * Test pembaruan data data berhasil.
     *
     * Skenario:
     * - ID valid dan data baru yang dikirimkan memenuhi syarat.
     * - Repository menemukan model terkait dan menerapkan perubahan.
     * - Data berhasil tersimpan.
     *
     * Ekspektasi:
     * - Method mengembalikan status sukses (true) yang menandakan keberhasilan update.
     */
    public function test_update_successfully()
    {
        $id = '550e8400-e29b-41d4-a716-446655440001';
        $data = [
            'project_id' => 'proj-1',
            'work_name' => 'Galian Tanah',
            'worker_category_id' => 1
        ];
        
        $mockModel = new TakeOffSheet();

        $this->takeOffSheetRepository
            ->shouldReceive('find')
            ->once()
            ->with($id)
            ->andReturn($mockModel);

        $this->takeOffSheetRepository
            ->shouldReceive('getProjectStatus')
            ->once()
            ->with($data['project_id'])
            ->andReturn('draft');

        $this->takeOffSheetRepository
            ->shouldReceive('existsByNameExcept')
            ->once()
            ->with($id, $data['work_name'], $data['project_id'], $data['worker_category_id'])
            ->andReturn(false);

        DB::shouldReceive('transaction')
            ->once()
            ->andReturnUsing(function ($callback) {
                return $callback();
            });

        $this->takeOffSheetRepository
            ->shouldReceive('update')
            ->once()
            ->with($mockModel, $data)
            ->andReturn(true);

        $result = $this->takeOffSheetService->update($id, $data);

        $this->assertTrue($result);
    }

    /**
     * Test penghapusan project gagal.
     *
     * Skenario:
     * - Permintaan hapus ditolak karena aturan bisnis tertentu (misal: project sudah disetujui atau terikat data lain).
     * - Repository/Service mendeteksi pembatasan penghapusan tersebut.
     *
     * Ekspektasi:
     * - DomainException dilempar untuk mencegah hilangnya data penting.
     */
    public function test_delete_throws_exception_if_project_approved()
    {
        $id = '550e8400-e29b-41d4-a716-446655440001';
        $mockModel = new TakeOffSheet();
        $mockModel->project_id = 'proj-1';

        $this->takeOffSheetRepository
            ->shouldReceive('find')
            ->once()
            ->with($id)
            ->andReturn($mockModel);

        $this->takeOffSheetRepository
            ->shouldReceive('getProjectStatus')
            ->once()
            ->with($mockModel->project_id)
            ->andReturn('approved');

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Proyek sudah disetujui, tidak dapat mengubah data!');

        $this->takeOffSheetService->delete($id);
    }

    /**
     * Test penghapusan data data berhasil.
     *
     * Skenario:
     * - ID data valid dan tidak melanggar aturan foreign key atau batasan hapus.
     * - Repository mengeksekusi penghapusan dari sumber data.
     *
     * Ekspektasi:
     * - Data data berhasil dihapus sepenuhnya (mengembalikan true).
     */
    public function test_delete_successfully()
    {
        $id = '550e8400-e29b-41d4-a716-446655440001';
        $mockModel = new TakeOffSheet();
        $mockModel->project_id = 'proj-1';

        $this->takeOffSheetRepository
            ->shouldReceive('find')
            ->once()
            ->with($id)
            ->andReturn($mockModel);

        $this->takeOffSheetRepository
            ->shouldReceive('getProjectStatus')
            ->once()
            ->with($mockModel->project_id)
            ->andReturn('draft');

        $this->takeOffSheetRepository
            ->shouldReceive('delete')
            ->once()
            ->with($mockModel)
            ->andReturn(true);

        $result = $this->takeOffSheetService->delete($id);

        $this->assertTrue($result);
    }
}

