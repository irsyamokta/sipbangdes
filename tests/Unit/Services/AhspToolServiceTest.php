<?php

namespace Tests\Unit\Services;

use App\Models\AhspComponentTool;
use App\Modules\Ahsp\Repositories\AhspToolRepository;
use App\Modules\Ahsp\Repositories\AhspRepository;
use App\Modules\Ahsp\Services\AhspToolService;
use DomainException;
use Mockery;
use Tests\TestCase;

class AhspToolServiceTest extends TestCase
{
    protected $ahspToolRepository;
    protected $ahspRepository;
    protected $ahspToolService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->ahspToolRepository = Mockery::mock(AhspToolRepository::class);
        $this->ahspRepository = Mockery::mock(AhspRepository::class);

        $this->ahspToolService = new AhspToolService(
            $this->ahspToolRepository,
            $this->ahspRepository
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test pengambilan data komponen alat AHSP.
     *
     * Skenario:
     * - Method pemanggilan data dieksekusi dengan parameter yang dibutuhkan.
     * - Repository mencari dan mengembalikan sekumpulan data.
     *
     * Ekspektasi:
     * - Service mengembalikan data komponen alat AHSP secara utuh yang diharapkan.
     */
    public function test_get_ahsp_tools_returns_data()
    {
        $ahspId = '550e8400-e29b-41d4-a716-446655440001';
        $expectedResult = collect([]);

        $this->ahspToolRepository
            ->shouldReceive('getAhspTools')
            ->once()
            ->with($ahspId)
            ->andReturn($expectedResult);

        $result = $this->ahspToolService->getAhspTools($ahspId);

        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Test pembuatan komponen alat AHSP gagal karena kondisi tidak terpenuhi.
     *
     * Skenario:
     * - Data pembuatan komponen alat AHSP dikirimkan namun melanggar validasi bisnis (misal: duplikat nama/kode).
     * - Repository mendeteksi pelanggaran tersebut.
     *
     * Ekspektasi:
     * - DomainException dilempar dengan pesan error yang relevan untuk mencegah duplikasi.
     */
    public function test_create_ahsp_tool_throws_exception_if_ahsp_not_found()
    {
        $data = ['ahsp_id' => 99, 'tool_id' => 1];

        $this->ahspRepository
            ->shouldReceive('exists')
            ->once()
            ->with($data['ahsp_id'])
            ->andReturn(false);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('AHSP tidak ditemukan.');

        $this->ahspToolService->createAhspTool($data);
    }

    /**
     * Test pembuatan komponen alat AHSP gagal karena kondisi tidak terpenuhi.
     *
     * Skenario:
     * - Data pembuatan komponen alat AHSP dikirimkan namun melanggar validasi bisnis (misal: duplikat nama/kode).
     * - Repository mendeteksi pelanggaran tersebut.
     *
     * Ekspektasi:
     * - DomainException dilempar dengan pesan error yang relevan untuk mencegah duplikasi.
     */
    public function test_create_ahsp_tool_throws_exception_if_tool_duplicate()
    {
        $data = ['ahsp_id' => 1, 'tool_id' => 1];

        $this->ahspRepository
            ->shouldReceive('exists')
            ->once()
            ->with($data['ahsp_id'])
            ->andReturn(true);

        $this->ahspToolRepository
            ->shouldReceive('existsInAhsp')
            ->once()
            ->with($data['ahsp_id'], $data['tool_id'])
            ->andReturn(true);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Nama alat dengan satuan tersebut sudah ada.');

        $this->ahspToolService->createAhspTool($data);
    }

    /**
     * Test pembuatan data komponen alat AHSP baru berhasil.
     *
     * Skenario:
     * - Data yang diberikan lolos semua validasi bisnis.
     * - Database transaction memastikan konsistensi.
     * - Repository memproses penyimpanan data ke tabel terkait.
     *
     * Ekspektasi:
     * - Data komponen alat AHSP berhasil dibuat dan model baru dikembalikan oleh service.
     */
    public function test_create_ahsp_tool_successfully()
    {
        $data = ['ahsp_id' => 1, 'tool_id' => 1];
        $mockModel = Mockery::mock(AhspComponentTool::class);

        $this->ahspRepository
            ->shouldReceive('exists')
            ->once()
            ->with($data['ahsp_id'])
            ->andReturn(true);

        $this->ahspToolRepository
            ->shouldReceive('existsInAhsp')
            ->once()
            ->with($data['ahsp_id'], $data['tool_id'])
            ->andReturn(false);

        $this->ahspToolRepository
            ->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn($mockModel);

        $result = $this->ahspToolService->createAhspTool($data);

        $this->assertEquals($mockModel, $result);
    }

    /**
     * Test pembaruan komponen alat AHSP gagal karena kondisi tidak terpenuhi.
     *
     * Skenario:
     * - ID dan data pembaruan dikirimkan.
     * - Terjadi konflik bisnis, seperti penggunaan nama yang sudah dipakai data lain.
     * - Repository menolak eksekusi karena pengecekan eksistensi.
     *
     * Ekspektasi:
     * - DomainException dilempar untuk mencegah penyimpanan data tidak konsisten.
     */
    public function test_update_ahsp_tool_throws_exception_if_duplicate()
    {
        $id = '550e8400-e29b-41d4-a716-446655440001';
        $data = ['ahsp_id' => 1, 'tool_id' => 2];

        $this->ahspToolRepository
            ->shouldReceive('existsInAhspExcept')
            ->once()
            ->with($id, $data['ahsp_id'], $data['tool_id'])
            ->andReturn(true);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Nama alat dengan satuan tersebut sudah ada.');

        $this->ahspToolService->updateAhspTool($id, $data);
    }

    /**
     * Test pembaruan data komponen alat AHSP berhasil.
     *
     * Skenario:
     * - ID valid dan data baru yang dikirimkan memenuhi syarat.
     * - Repository menemukan model terkait dan menerapkan perubahan.
     * - Data berhasil tersimpan.
     *
     * Ekspektasi:
     * - Method mengembalikan status sukses (true) yang menandakan keberhasilan update.
     */
    public function test_update_ahsp_tool_successfully()
    {
        $id = '550e8400-e29b-41d4-a716-446655440001';
        $data = ['ahsp_id' => 1, 'tool_id' => 2];
        $mockModel = Mockery::mock(AhspComponentTool::class);

        $this->ahspToolRepository
            ->shouldReceive('existsInAhspExcept')
            ->once()
            ->with($id, $data['ahsp_id'], $data['tool_id'])
            ->andReturn(false);

        $this->ahspToolRepository
            ->shouldReceive('find')
            ->once()
            ->with($id)
            ->andReturn($mockModel);

        $this->ahspToolRepository
            ->shouldReceive('update')
            ->once()
            ->with($mockModel, $data)
            ->andReturn(true);

        $result = $this->ahspToolService->updateAhspTool($id, $data);

        $this->assertTrue($result);
    }

    /**
     * Test penghapusan data komponen alat AHSP berhasil.
     *
     * Skenario:
     * - ID komponen alat AHSP valid dan tidak melanggar aturan foreign key atau batasan hapus.
     * - Repository mengeksekusi penghapusan dari sumber data.
     *
     * Ekspektasi:
     * - Data komponen alat AHSP berhasil dihapus sepenuhnya (mengembalikan true).
     */
    public function test_delete_ahsp_tool_successfully()
    {
        $id = '550e8400-e29b-41d4-a716-446655440001';
        $mockModel = Mockery::mock(AhspComponentTool::class);

        $this->ahspToolRepository
            ->shouldReceive('find')
            ->once()
            ->with($id)
            ->andReturn($mockModel);

        $this->ahspToolRepository
            ->shouldReceive('delete')
            ->once()
            ->with($mockModel)
            ->andReturn(true);

        $result = $this->ahspToolService->deleteAhspTool($id);

        $this->assertTrue($result);
    }
}

