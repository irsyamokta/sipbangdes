<?php

namespace Tests\Unit\Services;

use App\Models\ProjectExpenditure;
use App\Modules\Project\Repositories\ProjectExpenditureRepository;
use App\Modules\Project\Services\ProjectExpenditureService;
use App\Modules\Rab\Services\RabService;
use DomainException;
use Mockery;
use Tests\TestCase;

class ProjectExpenditureServiceTest extends TestCase
{
    protected $projectExpenditureRepository;
    protected $rabService;
    protected $projectExpenditureService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->projectExpenditureRepository = Mockery::mock(ProjectExpenditureRepository::class);
        $this->rabService = Mockery::mock(RabService::class);

        $this->projectExpenditureService = new ProjectExpenditureService(
            $this->projectExpenditureRepository,
            $this->rabService
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test pengambilan data data.
     *
     * Skenario:
     * - Method pemanggilan data dieksekusi dengan parameter yang dibutuhkan.
     * - Repository mencari dan mengembalikan sekumpulan data.
     *
     * Ekspektasi:
     * - Service mengembalikan data data secara utuh yang diharapkan.
     */
    public function test_get_detail_returns_aggregated_data()
    {
        $projectId = 'proj-1';
        $mockProject = Mockery::mock();
        $mockProject->total_budget = 10000000;

        $this->projectExpenditureRepository
            ->shouldReceive('getProjectDetail')
            ->once()
            ->with($projectId)
            ->andReturn($mockProject);

        $this->projectExpenditureRepository
            ->shouldReceive('getTotalRealization')
            ->once()
            ->with($projectId)
            ->andReturn(2500000);

        $result = $this->projectExpenditureService->getDetail($projectId);

        $this->assertEquals($mockProject, $result['project']);
        $this->assertEquals(10000000, $result['totalBudget']);
        $this->assertEquals(2500000, $result['totalRealization']);
        $this->assertEquals(7500000, $result['remainingBudget']);
        $this->assertEquals(25.0, $result['percentage']);
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
    public function test_create_throws_exception_if_budget_unavailable()
    {
        $data = ['project_id' => 'proj-1', 'nominal' => 1000000];

        $this->rabService
            ->shouldReceive('generate')
            ->once()
            ->with($data['project_id'])
            ->andReturn(['summary' => ['grand_total' => 0]]);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Total anggaran tidak tersedia');

        $this->projectExpenditureService->create($data);
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
    public function test_create_throws_exception_if_nominal_exceeds_budget()
    {
        $data = ['project_id' => 'proj-1', 'nominal' => 6000000];

        $this->rabService
            ->shouldReceive('generate')
            ->once()
            ->with($data['project_id'])
            ->andReturn(['summary' => ['grand_total' => 10000000]]);

        $this->projectExpenditureRepository
            ->shouldReceive('getTotalRealization')
            ->once()
            ->with($data['project_id'])
            ->andReturn(5000000);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Anggaran tidak mencukupi. Sisa: Rp 5.000.000');

        $this->projectExpenditureService->create($data);
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
        $data = ['project_id' => 'proj-1', 'nominal' => 4000000];
        $mockModel = Mockery::mock(ProjectExpenditure::class);

        $this->rabService
            ->shouldReceive('generate')
            ->once()
            ->with($data['project_id'])
            ->andReturn(['summary' => ['grand_total' => 10000000]]);

        $this->projectExpenditureRepository
            ->shouldReceive('getTotalRealization')
            ->once()
            ->with($data['project_id'])
            ->andReturn(5000000);

        $this->projectExpenditureRepository
            ->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn($mockModel);

        $result = $this->projectExpenditureService->create($data);

        $this->assertEquals($mockModel, $result);
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
    public function test_update_throws_exception_if_nominal_exceeds_budget()
    {
        $id = '550e8400-e29b-41d4-a716-446655440001';
        $data = ['nominal' => 8000000];
        $mockModel = new ProjectExpenditure();
        $mockModel->project_id = 'proj-1';

        $this->projectExpenditureRepository
            ->shouldReceive('find')
            ->once()
            ->with($id)
            ->andReturn($mockModel);

        $this->rabService
            ->shouldReceive('generate')
            ->once()
            ->with('proj-1')
            ->andReturn(['summary' => ['grand_total' => 10000000]]);

        $this->projectExpenditureRepository
            ->shouldReceive('getTotalRealizationExcept')
            ->once()
            ->with('proj-1', $id)
            ->andReturn(3000000);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Anggaran tidak mencukupi. Sisa: Rp 7.000.000');

        $this->projectExpenditureService->update($id, $data);
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
        $data = ['nominal' => 2000000];
        $mockModel = Mockery::mock(ProjectExpenditure::class)->makePartial();
        $mockModel->project_id = 'proj-1';

        $this->projectExpenditureRepository
            ->shouldReceive('find')
            ->once()
            ->with($id)
            ->andReturn($mockModel);

        $this->rabService
            ->shouldReceive('generate')
            ->once()
            ->with('proj-1')
            ->andReturn(['summary' => ['grand_total' => 10000000]]);

        $this->projectExpenditureRepository
            ->shouldReceive('getTotalRealizationExcept')
            ->once()
            ->with('proj-1', $id)
            ->andReturn(3000000);

        $mockModel->shouldReceive('update')
            ->once()
            ->with($data)
            ->andReturn(true);

        $this->projectExpenditureService->update($id, $data);

        $this->assertTrue(true);
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
        $mockModel = Mockery::mock(ProjectExpenditure::class);

        $this->projectExpenditureRepository
            ->shouldReceive('find')
            ->once()
            ->with($id)
            ->andReturn($mockModel);

        $mockModel->shouldReceive('delete')
            ->once()
            ->andReturn(true);

        $this->projectExpenditureService->delete($id);

        $this->assertTrue(true);
    }
}

