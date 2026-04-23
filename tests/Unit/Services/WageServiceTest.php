<?php

namespace Tests\Unit\Services;

use App\Contracts\CodeGeneratorInterface;
use App\Models\MasterWage;
use App\Modules\Wage\Repositories\WageRepository;
use App\Modules\Wage\Services\WageService;
use Illuminate\Support\Facades\DB;
use Mockery;
use Tests\TestCase;

class WageServiceTest extends TestCase
{
    protected $wageRepository;

    protected $codeGenerator;

    protected $wageService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->wageRepository =
            Mockery::mock(WageRepository::class);

        $this->codeGenerator =
            Mockery::mock(CodeGeneratorInterface::class);

        $this->wageService = new WageService(
            $this->wageRepository,
            $this->codeGenerator
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test pengambilan data upah menggunakan pagination.
     *
     * Skenario:
     * - Parameter pencarian (search) diberikan.
     * - Pagination diaktifkan dengan jumlah data per halaman tertentu.
     * - Repository memanggil method getPaginated.
     *
     * Ekspektasi:
     * - Service mengembalikan hasil pagination
     *   yang sama dengan hasil dari repository.
     */
    public function test_get_wages_with_pagination()
    {
        $search = 'tukang';
        $perPage = 10;

        $this->wageRepository
            ->shouldReceive('getPaginated')
            ->once()
            ->with($search, $perPage)
            ->andReturn('paginated_result');

        $result = $this->wageService->getWages(
            $search,
            true,
            $perPage
        );

        $this->assertEquals(
            'paginated_result',
            $result
        );
    }

    /**
     * Test pengambilan seluruh data upah tanpa pagination.
     *
     * Skenario:
     * - Parameter pencarian (search) diberikan.
     * - Pagination dinonaktifkan.
     * - Repository memanggil method getAll.
     *
     * Ekspektasi:
     * - Service mengembalikan seluruh data upah
     *   sesuai hasil dari repository.
     */
    public function test_get_wages_without_pagination()
    {
        $search = 'tukang';

        $this->wageRepository
            ->shouldReceive('getAll')
            ->once()
            ->with($search)
            ->andReturn('all_result');

        $result = $this->wageService->getWages(
            $search,
            false
        );

        $this->assertEquals(
            'all_result',
            $result
        );
    }

    /**
     * Test pembuatan data upah baru berhasil.
     *
     * Skenario:
     * - Database transaction dijalankan.
     * - Code generator menghasilkan kode upah unik.
     * - Repository membuat data upah baru
     *   dengan kode yang telah dihasilkan.
     *
     * Ekspektasi:
     * - Data upah berhasil dibuat.
     * - Kode upah otomatis tersimpan dalam data.
     * - Method createWage mengembalikan
     *   instance upah hasil repository.
     */
    public function test_create_wage_successfully()
    {
        $data = [
            'name' => 'Tukang Batu',
            'unit' => 'OH',
            'price' => 125000,
        ];

        DB::shouldReceive('transaction')
            ->once()
            ->andReturnUsing(function ($callback) {
                return $callback();
            });

        $this->codeGenerator
            ->shouldReceive('generate')
            ->once()
            ->with(
                MasterWage::class,
                'code',
                'UPH'
            )
            ->andReturn('UPH001');

        $wageMock =
            Mockery::mock(MasterWage::class);

        $this->wageRepository
            ->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($arg) {
                return isset($arg['code'])
                    && $arg['code'] === 'UPH001';
            }))
            ->andReturn($wageMock);

        $result = $this->wageService
            ->createWage($data);

        $this->assertEquals(
            $wageMock,
            $result
        );
    }

    /**
     * Test pembaruan data upah berhasil.
     *
     * Skenario:
     * - Data upah ditemukan berdasarkan ID.
     * - Repository menjalankan proses update
     *   dengan data baru yang diberikan.
     *
     * Ekspektasi:
     * - Method updateWage mengembalikan nilai true
     *   sebagai indikasi bahwa proses update berhasil.
     */
    public function test_update_wage_successfully()
    {
        $wageId = '550e8400-e29b-41d4-a716-446655440030';

        $data = [
            'name' => 'Mandor',
            'unit' => 'OH',
            'price' => 135000,
        ];

        $wageMock =
            Mockery::mock(MasterWage::class);

        $this->wageRepository
            ->shouldReceive('find')
            ->once()
            ->with($wageId)
            ->andReturn($wageMock);

        $this->wageRepository
            ->shouldReceive('update')
            ->once()
            ->with($wageMock, $data)
            ->andReturn(true);

        $result = $this->wageService
            ->updateWage($wageId, $data);

        $this->assertTrue($result);
    }

    /**
     * Test penghapusan data upah berhasil.
     *
     * Skenario:
     * - Data upah ditemukan berdasarkan ID.
     * - Repository menjalankan proses delete
     *   terhadap data upah tersebut.
     *
     * Ekspektasi:
     * - Method deleteWage mengembalikan nilai true
     *   sebagai indikasi bahwa proses penghapusan berhasil.
     */
    public function test_delete_wage_successfully()
    {
        $wageId = '550e8400-e29b-41d4-a716-446655440031';

        $wage = new MasterWage;

        $this->wageRepository
            ->shouldReceive('find')
            ->once()
            ->with($wageId)
            ->andReturn($wage);

        $this->wageRepository
            ->shouldReceive('delete')
            ->once()
            ->with($wage)
            ->andReturn(true);

        $result = $this->wageService
            ->deleteWage($wageId);

        $this->assertTrue($result);
    }
}
