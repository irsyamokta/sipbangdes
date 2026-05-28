<?php

namespace Tests\Unit\Services;

use App\Models\AhspComponentMaterial;
use App\Modules\Ahsp\Repositories\AhspMaterialRepository;
use App\Modules\Ahsp\Repositories\AhspRepository;
use App\Modules\Ahsp\Services\AhspMaterialService;
use DomainException;
use Mockery;
use Tests\TestCase;

class AhspMaterialServiceTest extends TestCase
{
    protected $ahspMaterialRepository;
    protected $ahspRepository;
    protected $ahspMaterialService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->ahspMaterialRepository = Mockery::mock(AhspMaterialRepository::class);
        $this->ahspRepository = Mockery::mock(AhspRepository::class);

        $this->ahspMaterialService = new AhspMaterialService(
            $this->ahspMaterialRepository,
            $this->ahspRepository
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test pengambilan data komponen material AHSP.
     *
     * Skenario:
     * - Method pemanggilan data dieksekusi dengan parameter yang dibutuhkan.
     * - Repository mencari dan mengembalikan sekumpulan data.
     *
     * Ekspektasi:
     * - Service mengembalikan data komponen material AHSP secara utuh yang diharapkan.
     */
    public function test_get_ahsp_materials_returns_data()
    {
        $ahspId = '550e8400-e29b-41d4-a716-446655440001';
        $expectedResult = collect([]);

        $this->ahspMaterialRepository
            ->shouldReceive('getAhspMaterials')
            ->once()
            ->with($ahspId)
            ->andReturn($expectedResult);

        $result = $this->ahspMaterialService->getAhspMaterials($ahspId);

        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Test pembuatan komponen material AHSP gagal karena kondisi tidak terpenuhi.
     *
     * Skenario:
     * - Data pembuatan komponen material AHSP dikirimkan namun melanggar validasi bisnis (misal: duplikat nama/kode).
     * - Repository mendeteksi pelanggaran tersebut.
     *
     * Ekspektasi:
     * - DomainException dilempar dengan pesan error yang relevan untuk mencegah duplikasi.
     */
    public function test_create_ahsp_material_throws_exception_if_ahsp_not_found()
    {
        $data = ['ahsp_id' => 99, 'material_id' => 1];

        $this->ahspRepository
            ->shouldReceive('exists')
            ->once()
            ->with($data['ahsp_id'])
            ->andReturn(false);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('AHSP tidak ditemukan.');

        $this->ahspMaterialService->createAhspMaterial($data);
    }

    /**
     * Test pembuatan komponen material AHSP gagal karena kondisi tidak terpenuhi.
     *
     * Skenario:
     * - Data pembuatan komponen material AHSP dikirimkan namun melanggar validasi bisnis (misal: duplikat nama/kode).
     * - Repository mendeteksi pelanggaran tersebut.
     *
     * Ekspektasi:
     * - DomainException dilempar dengan pesan error yang relevan untuk mencegah duplikasi.
     */
    public function test_create_ahsp_material_throws_exception_if_material_duplicate()
    {
        $data = ['ahsp_id' => 1, 'material_id' => 1];

        $this->ahspRepository
            ->shouldReceive('exists')
            ->once()
            ->with($data['ahsp_id'])
            ->andReturn(true);

        $this->ahspMaterialRepository
            ->shouldReceive('existsInAhsp')
            ->once()
            ->with($data['ahsp_id'], $data['material_id'])
            ->andReturn(true);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Nama material dengan satuan tersebut sudah ada.');

        $this->ahspMaterialService->createAhspMaterial($data);
    }

    /**
     * Test pembuatan data komponen material AHSP baru berhasil.
     *
     * Skenario:
     * - Data yang diberikan lolos semua validasi bisnis.
     * - Database transaction memastikan konsistensi.
     * - Repository memproses penyimpanan data ke tabel terkait.
     *
     * Ekspektasi:
     * - Data komponen material AHSP berhasil dibuat dan model baru dikembalikan oleh service.
     */
    public function test_create_ahsp_material_successfully()
    {
        $data = ['ahsp_id' => 1, 'material_id' => 1];
        $mockModel = Mockery::mock(AhspComponentMaterial::class);

        $this->ahspRepository
            ->shouldReceive('exists')
            ->once()
            ->with($data['ahsp_id'])
            ->andReturn(true);

        $this->ahspMaterialRepository
            ->shouldReceive('existsInAhsp')
            ->once()
            ->with($data['ahsp_id'], $data['material_id'])
            ->andReturn(false);

        $this->ahspMaterialRepository
            ->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn($mockModel);

        $result = $this->ahspMaterialService->createAhspMaterial($data);

        $this->assertEquals($mockModel, $result);
    }

    /**
     * Test pembaruan komponen material AHSP gagal karena kondisi tidak terpenuhi.
     *
     * Skenario:
     * - ID dan data pembaruan dikirimkan.
     * - Terjadi konflik bisnis, seperti penggunaan nama yang sudah dipakai data lain.
     * - Repository menolak eksekusi karena pengecekan eksistensi.
     *
     * Ekspektasi:
     * - DomainException dilempar untuk mencegah penyimpanan data tidak konsisten.
     */
    public function test_update_ahsp_material_throws_exception_if_duplicate()
    {
        $id = '550e8400-e29b-41d4-a716-446655440001';
        $data = ['ahsp_id' => 1, 'material_id' => 2];

        $this->ahspMaterialRepository
            ->shouldReceive('existsInAhspExcept')
            ->once()
            ->with($id, $data['ahsp_id'], $data['material_id'])
            ->andReturn(true);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Nama material dengan satuan tersebut sudah ada.');

        $this->ahspMaterialService->updateAhspMaterial($id, $data);
    }

    /**
     * Test pembaruan data komponen material AHSP berhasil.
     *
     * Skenario:
     * - ID valid dan data baru yang dikirimkan memenuhi syarat.
     * - Repository menemukan model terkait dan menerapkan perubahan.
     * - Data berhasil tersimpan.
     *
     * Ekspektasi:
     * - Method mengembalikan status sukses (true) yang menandakan keberhasilan update.
     */
    public function test_update_ahsp_material_successfully()
    {
        $id = '550e8400-e29b-41d4-a716-446655440001';
        $data = ['ahsp_id' => 1, 'material_id' => 2];
        $mockModel = Mockery::mock(AhspComponentMaterial::class);

        $this->ahspMaterialRepository
            ->shouldReceive('existsInAhspExcept')
            ->once()
            ->with($id, $data['ahsp_id'], $data['material_id'])
            ->andReturn(false);

        $this->ahspMaterialRepository
            ->shouldReceive('find')
            ->once()
            ->with($id)
            ->andReturn($mockModel);

        $this->ahspMaterialRepository
            ->shouldReceive('update')
            ->once()
            ->with($mockModel, $data)
            ->andReturn(true);

        $result = $this->ahspMaterialService->updateAhspMaterial($id, $data);

        $this->assertTrue($result);
    }

    /**
     * Test penghapusan data komponen material AHSP berhasil.
     *
     * Skenario:
     * - ID komponen material AHSP valid dan tidak melanggar aturan foreign key atau batasan hapus.
     * - Repository mengeksekusi penghapusan dari sumber data.
     *
     * Ekspektasi:
     * - Data komponen material AHSP berhasil dihapus sepenuhnya (mengembalikan true).
     */
    public function test_delete_ahsp_material_successfully()
    {
        $id = '550e8400-e29b-41d4-a716-446655440001';
        $mockModel = Mockery::mock(AhspComponentMaterial::class);

        $this->ahspMaterialRepository
            ->shouldReceive('find')
            ->once()
            ->with($id)
            ->andReturn($mockModel);

        $this->ahspMaterialRepository
            ->shouldReceive('delete')
            ->once()
            ->with($mockModel)
            ->andReturn(true);

        $result = $this->ahspMaterialService->deleteAhspMaterial($id);

        $this->assertTrue($result);
    }
}

