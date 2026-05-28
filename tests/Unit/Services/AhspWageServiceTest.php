<?php

namespace Tests\Unit\Services;

use App\Models\AhspComponentWage;
use App\Modules\Ahsp\Repositories\AhspWageRepository;
use App\Modules\Ahsp\Repositories\AhspRepository;
use App\Modules\Ahsp\Services\AhspWageService;
use DomainException;
use Mockery;
use Tests\TestCase;

class AhspWageServiceTest extends TestCase
{
    protected $ahspWageRepository;
    protected $ahspRepository;
    protected $ahspWageService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->ahspWageRepository = Mockery::mock(AhspWageRepository::class);
        $this->ahspRepository = Mockery::mock(AhspRepository::class);

        $this->ahspWageService = new AhspWageService(
            $this->ahspWageRepository,
            $this->ahspRepository
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test pengambilan data komponen upah AHSP.
     *
     * Skenario:
     * - Method pemanggilan data dieksekusi dengan parameter yang dibutuhkan.
     * - Repository mencari dan mengembalikan sekumpulan data.
     *
     * Ekspektasi:
     * - Service mengembalikan data komponen upah AHSP secara utuh yang diharapkan.
     */
    public function test_get_ahsp_wages_returns_data()
    {
        $ahspId = '550e8400-e29b-41d4-a716-446655440001';
        $expectedResult = collect([]);

        $this->ahspWageRepository
            ->shouldReceive('getAhspWages')
            ->once()
            ->with($ahspId)
            ->andReturn($expectedResult);

        $result = $this->ahspWageService->getAhspWages($ahspId);

        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Test pembuatan komponen upah AHSP gagal karena kondisi tidak terpenuhi.
     *
     * Skenario:
     * - Data pembuatan komponen upah AHSP dikirimkan namun melanggar validasi bisnis (misal: duplikat nama/kode).
     * - Repository mendeteksi pelanggaran tersebut.
     *
     * Ekspektasi:
     * - DomainException dilempar dengan pesan error yang relevan untuk mencegah duplikasi.
     */
    public function test_create_ahsp_wage_throws_exception_if_ahsp_not_found()
    {
        $data = ['ahsp_id' => 99, 'wage_id' => 1];

        $this->ahspRepository
            ->shouldReceive('exists')
            ->once()
            ->with($data['ahsp_id'])
            ->andReturn(false);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('AHSP tidak ditemukan.');

        $this->ahspWageService->createAhspWage($data);
    }

    /**
     * Test pembuatan komponen upah AHSP gagal karena kondisi tidak terpenuhi.
     *
     * Skenario:
     * - Data pembuatan komponen upah AHSP dikirimkan namun melanggar validasi bisnis (misal: duplikat nama/kode).
     * - Repository mendeteksi pelanggaran tersebut.
     *
     * Ekspektasi:
     * - DomainException dilempar dengan pesan error yang relevan untuk mencegah duplikasi.
     */
    public function test_create_ahsp_wage_throws_exception_if_wage_duplicate()
    {
        $data = ['ahsp_id' => 1, 'wage_id' => 1];

        $this->ahspRepository
            ->shouldReceive('exists')
            ->once()
            ->with($data['ahsp_id'])
            ->andReturn(true);

        $this->ahspWageRepository
            ->shouldReceive('existsInAhsp')
            ->once()
            ->with($data['ahsp_id'], $data['wage_id'])
            ->andReturn(true);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Nama upah dengan satuan tersebut sudah ada.');

        $this->ahspWageService->createAhspWage($data);
    }

    /**
     * Test pembuatan data komponen upah AHSP baru berhasil.
     *
     * Skenario:
     * - Data yang diberikan lolos semua validasi bisnis.
     * - Database transaction memastikan konsistensi.
     * - Repository memproses penyimpanan data ke tabel terkait.
     *
     * Ekspektasi:
     * - Data komponen upah AHSP berhasil dibuat dan model baru dikembalikan oleh service.
     */
    public function test_create_ahsp_wage_successfully()
    {
        $data = ['ahsp_id' => 1, 'wage_id' => 1];
        $mockModel = Mockery::mock(AhspComponentWage::class);

        $this->ahspRepository
            ->shouldReceive('exists')
            ->once()
            ->with($data['ahsp_id'])
            ->andReturn(true);

        $this->ahspWageRepository
            ->shouldReceive('existsInAhsp')
            ->once()
            ->with($data['ahsp_id'], $data['wage_id'])
            ->andReturn(false);

        $this->ahspWageRepository
            ->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn($mockModel);

        $result = $this->ahspWageService->createAhspWage($data);

        $this->assertEquals($mockModel, $result);
    }

    /**
     * Test pembaruan komponen upah AHSP gagal karena kondisi tidak terpenuhi.
     *
     * Skenario:
     * - ID dan data pembaruan dikirimkan.
     * - Terjadi konflik bisnis, seperti penggunaan nama yang sudah dipakai data lain.
     * - Repository menolak eksekusi karena pengecekan eksistensi.
     *
     * Ekspektasi:
     * - DomainException dilempar untuk mencegah penyimpanan data tidak konsisten.
     */
    public function test_update_ahsp_wage_throws_exception_if_duplicate()
    {
        $id = '550e8400-e29b-41d4-a716-446655440001';
        $data = ['ahsp_id' => 1, 'wage_id' => 2];

        $this->ahspWageRepository
            ->shouldReceive('existsInAhspExcept')
            ->once()
            ->with($id, $data['ahsp_id'], $data['wage_id'])
            ->andReturn(true);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Nama upah dengan satuan tersebut sudah ada.');

        $this->ahspWageService->updateAhspWage($id, $data);
    }

    /**
     * Test pembaruan data komponen upah AHSP berhasil.
     *
     * Skenario:
     * - ID valid dan data baru yang dikirimkan memenuhi syarat.
     * - Repository menemukan model terkait dan menerapkan perubahan.
     * - Data berhasil tersimpan.
     *
     * Ekspektasi:
     * - Method mengembalikan status sukses (true) yang menandakan keberhasilan update.
     */
    public function test_update_ahsp_wage_successfully()
    {
        $id = '550e8400-e29b-41d4-a716-446655440001';
        $data = ['ahsp_id' => 1, 'wage_id' => 2];
        $mockModel = Mockery::mock(AhspComponentWage::class);

        $this->ahspWageRepository
            ->shouldReceive('existsInAhspExcept')
            ->once()
            ->with($id, $data['ahsp_id'], $data['wage_id'])
            ->andReturn(false);

        $this->ahspWageRepository
            ->shouldReceive('find')
            ->once()
            ->with($id)
            ->andReturn($mockModel);

        $this->ahspWageRepository
            ->shouldReceive('update')
            ->once()
            ->with($mockModel, $data)
            ->andReturn(true);

        $result = $this->ahspWageService->updateAhspWage($id, $data);

        $this->assertTrue($result);
    }

    /**
     * Test penghapusan data komponen upah AHSP berhasil.
     *
     * Skenario:
     * - ID komponen upah AHSP valid dan tidak melanggar aturan foreign key atau batasan hapus.
     * - Repository mengeksekusi penghapusan dari sumber data.
     *
     * Ekspektasi:
     * - Data komponen upah AHSP berhasil dihapus sepenuhnya (mengembalikan true).
     */
    public function test_delete_ahsp_wage_successfully()
    {
        $id = '550e8400-e29b-41d4-a716-446655440001';
        $mockModel = Mockery::mock(AhspComponentWage::class);

        $this->ahspWageRepository
            ->shouldReceive('find')
            ->once()
            ->with($id)
            ->andReturn($mockModel);

        $this->ahspWageRepository
            ->shouldReceive('delete')
            ->once()
            ->with($mockModel)
            ->andReturn(true);

        $result = $this->ahspWageService->deleteAhspWage($id);

        $this->assertTrue($result);
    }
}

