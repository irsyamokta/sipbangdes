<?php

namespace Tests\Unit\Services;

use App\Models\Project;
use App\Models\ProjectDocument;
use App\Modules\Project\Repositories\ProjectRepository;
use App\Modules\Project\Services\ProjectService;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use DomainException;
use Illuminate\Support\Facades\DB;
use Mockery;
use Tests\TestCase;
use Exception;

class ProjectServiceTest extends TestCase
{
    protected $projectRepository;
    protected $projectService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->projectRepository = Mockery::mock(ProjectRepository::class);

        $this->projectService = new ProjectService(
            $this->projectRepository
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test pengambilan data project.
     *
     * Skenario:
     * - Method pemanggilan data dieksekusi dengan parameter yang dibutuhkan.
     * - Repository mencari dan mengembalikan sekumpulan data.
     *
     * Ekspektasi:
     * - Service mengembalikan data project secara utuh yang diharapkan.
     */
    public function test_get_projects_returns_data()
    {
        $search = 'Drainase';
        $year = '2026';
        $expectedResult = collect([]);

        $this->projectRepository
            ->shouldReceive('getAll')
            ->once()
            ->with($search, $year)
            ->andReturn($expectedResult);

        $result = $this->projectService->getProjects($search, $year);

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
    public function test_create_project_throws_exception_if_duplicate()
    {
        $data = [
            'project_name' => 'Pembuatan Drainase',
            'budget_year' => '2026',
            'location' => 'Desa A'
        ];

        $this->projectRepository
            ->shouldReceive('isDuplicate')
            ->once()
            ->with($data['project_name'], $data['budget_year'], $data['location'])
            ->andReturn(true);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Proyek tersebut sudah ada.');

        $this->projectService->createProject($data);
    }

    /**
     * Test pembuatan data project baru berhasil.
     *
     * Skenario:
     * - Data yang diberikan lolos semua validasi bisnis.
     * - Database transaction memastikan konsistensi.
     * - Repository memproses penyimpanan data ke tabel terkait.
     *
     * Ekspektasi:
     * - Data project berhasil dibuat dan model baru dikembalikan oleh service.
     */
    public function test_create_project_successfully()
    {
        $data = [
            'project_name' => 'Pembuatan Drainase',
            'budget_year' => '2026',
            'location' => 'Desa A'
        ];
        $mockProject = Mockery::mock(Project::class);

        $this->projectRepository
            ->shouldReceive('isDuplicate')
            ->once()
            ->with($data['project_name'], $data['budget_year'], $data['location'])
            ->andReturn(false);

        DB::shouldReceive('transaction')
            ->once()
            ->andReturnUsing(function ($callback) {
                return $callback();
            });

        $this->projectRepository
            ->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn($mockProject);

        $result = $this->projectService->createProject($data);

        $this->assertEquals($mockProject, $result);
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
    public function test_update_project_throws_exception_if_duplicate()
    {
        $id = '550e8400-e29b-41d4-a716-446655440001';
        $data = [
            'project_name' => 'Pembuatan Drainase',
            'budget_year' => '2026',
            'location' => 'Desa B'
        ];
        
        $mockProject = new Project();
        $mockProject->id = $id;

        $this->projectRepository
            ->shouldReceive('find')
            ->once()
            ->with($id)
            ->andReturn($mockProject);

        $this->projectRepository
            ->shouldReceive('isDuplicate')
            ->once()
            ->with($data['project_name'], $data['budget_year'], $data['location'], $id)
            ->andReturn(true);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Proyek tersebut sudah ada.');

        $this->projectService->updateProject($id, $data);
    }

    /**
     * Test pembaruan data project berhasil.
     *
     * Skenario:
     * - ID valid dan data baru yang dikirimkan memenuhi syarat.
     * - Repository menemukan model terkait dan menerapkan perubahan.
     * - Data berhasil tersimpan.
     *
     * Ekspektasi:
     * - Method mengembalikan status sukses (true) yang menandakan keberhasilan update.
     */
    public function test_update_project_successfully()
    {
        $id = '550e8400-e29b-41d4-a716-446655440001';
        $data = [
            'project_name' => 'Pembuatan Drainase',
            'budget_year' => '2026',
            'location' => 'Desa B'
        ];
        
        $mockProject = new Project();
        $mockProject->id = $id;

        $this->projectRepository
            ->shouldReceive('find')
            ->once()
            ->with($id)
            ->andReturn($mockProject);

        $this->projectRepository
            ->shouldReceive('isDuplicate')
            ->once()
            ->with($data['project_name'], $data['budget_year'], $data['location'], $id)
            ->andReturn(false);

        $this->projectRepository
            ->shouldReceive('update')
            ->once()
            ->with($mockProject, $data)
            ->andReturn($mockProject);

        $result = $this->projectService->updateProject($id, $data);

        $this->assertEquals($mockProject, $result);
    }

    /**
     * Test penghapusan data project berhasil.
     *
     * Skenario:
     * - ID project valid dan tidak melanggar aturan foreign key atau batasan hapus.
     * - Repository mengeksekusi penghapusan dari sumber data.
     *
     * Ekspektasi:
     * - Data project berhasil dihapus sepenuhnya (mengembalikan true).
     */
    public function test_delete_project_successfully()
    {
        $id = '550e8400-e29b-41d4-a716-446655440001';
        $mockProject = new Project();
        $mockProject->id = $id;
        
        $mockDocument = new ProjectDocument();
        $mockDocument->public_id = 'public_id_1';
        $mockProject->setRelation('projectDocuments', collect([$mockDocument]));

        $this->projectRepository
            ->shouldReceive('find')
            ->once()
            ->with($id)
            ->andReturn($mockProject);

        DB::shouldReceive('transaction')
            ->once()
            ->andReturnUsing(function ($callback) {
                return $callback();
            });

        $uploadApiMock = Mockery::mock(\Cloudinary\Api\Upload\UploadApi::class);
        $uploadApiMock->shouldReceive('destroy')
            ->once()
            ->with('public_id_1');

        Cloudinary::shouldReceive('uploadApi')
            ->once()
            ->andReturn($uploadApiMock);

        $this->projectRepository
            ->shouldReceive('delete')
            ->once()
            ->with($mockProject)
            ->andReturn(true);

        $result = $this->projectService->deleteProject($id);

        $this->assertTrue($result);
    }
}

