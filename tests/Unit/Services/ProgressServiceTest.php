<?php

namespace Tests\Unit\Services;

use App\Models\ProjectProgress;
use App\Models\ProjectDocument;
use App\Modules\Project\Repositories\ProgressRepository;
use App\Modules\Project\Services\ProgressService;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mockery;
use Tests\TestCase;
use Exception;

class ProgressServiceTest extends TestCase
{
    protected $progressRepository;
    protected $progressService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->progressRepository = Mockery::mock(ProgressRepository::class);

        $this->progressService = new ProgressService(
            $this->progressRepository
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
    public function test_get_detail_returns_data()
    {
        $projectId = 'proj-1';
        $mockProject = Mockery::mock();

        $this->progressRepository
            ->shouldReceive('getProjectDetail')
            ->once()
            ->with($projectId)
            ->andReturn($mockProject);

        $this->progressRepository
            ->shouldReceive('getTotalProgress')
            ->once()
            ->with($projectId)
            ->andReturn(50);

        $result = $this->progressService->getDetail($projectId);

        $this->assertEquals($mockProject, $result['project']);
        $this->assertEquals(50, $result['totalProgress']);
    }

    /**
     * Test penghapusan data dokumen berhasil.
     *
     * Skenario:
     * - ID dokumen valid dan tidak melanggar aturan foreign key atau batasan hapus.
     * - Repository mengeksekusi penghapusan dari sumber data.
     *
     * Ekspektasi:
     * - Data dokumen berhasil dihapus sepenuhnya (mengembalikan true).
     */
    public function test_delete_document_successfully()
    {
        $documentId = 'doc-1';
        $mockDocument = new ProjectDocument();
        $mockDocument->public_id = 'public_id_1';

        $mockDocumentMock = Mockery::mock($mockDocument)->makePartial();
        $mockDocumentMock->shouldReceive('delete')
            ->once()
            ->andReturn(true);

        $this->progressRepository
            ->shouldReceive('getDocument')
            ->once()
            ->with($documentId)
            ->andReturn($mockDocumentMock);

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

        $result = $this->progressService->deleteDocument($documentId);

        $this->assertTrue($result);
    }

    /**
     * Test operasi terkait dokumen.
     *
     * Skenario:
     * - Parameter dan mock data disiapkan untuk pengujian operasi spesifik.
     * - Proses servis utama dipanggil.
     *
     * Ekspektasi:
     * - Fungsi tereksekusi dengan mengembalikan nilai yang tepat atau merubah state internal dengan benar.
     */
    public function test_delete_document_handles_cloudinary_exception()
    {
        $documentId = 'doc-1';
        $mockDocument = new ProjectDocument();
        $mockDocument->public_id = 'public_id_1';

        $mockDocumentMock = Mockery::mock($mockDocument)->makePartial();
        $mockDocumentMock->shouldReceive('delete')
            ->once()
            ->andReturn(true);

        $this->progressRepository
            ->shouldReceive('getDocument')
            ->once()
            ->with($documentId)
            ->andReturn($mockDocumentMock);

        DB::shouldReceive('transaction')
            ->once()
            ->andReturnUsing(function ($callback) {
                return $callback();
            });

        $uploadApiMock = Mockery::mock(\Cloudinary\Api\Upload\UploadApi::class);
        $uploadApiMock->shouldReceive('destroy')
            ->once()
            ->with('public_id_1')
            ->andThrow(new Exception('Cloudinary Error'));

        Cloudinary::shouldReceive('uploadApi')
            ->once()
            ->andReturn($uploadApiMock);

        Log::shouldReceive('error')
            ->once()
            ->with('Cloudinary Error');

        $result = $this->progressService->deleteDocument($documentId);

        $this->assertTrue($result);
    }
}

