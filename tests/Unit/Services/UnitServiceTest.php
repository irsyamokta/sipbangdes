<?php

namespace Tests\Unit\Services;

use App\Contracts\CodeGeneratorInterface;
use App\Models\MasterUnit;
use App\Modules\Unit\Repositories\UnitRepository;
use App\Modules\Unit\Services\UnitService;
use DomainException;
use Illuminate\Support\Facades\DB;
use Mockery;
use Tests\TestCase;

class UnitServiceTest extends TestCase
{
    protected $unitRepository;

    protected $codeGenerator;

    protected $unitService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->unitRepository = Mockery::mock(UnitRepository::class);
        $this->codeGenerator = Mockery::mock(CodeGeneratorInterface::class);

        $this->unitService = new UnitService(
            $this->unitRepository,
            $this->codeGenerator
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test pengambilan data satuan menggunakan pagination.
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
    public function test_get_units_with_pagination()
    {
        $search = 'meter';
        $perPage = 10;

        $this->unitRepository
            ->shouldReceive('getPaginated')
            ->once()
            ->with($search, $perPage)
            ->andReturn('paginated_result');

        $result = $this->unitService->getUnits(
            $search,
            true,
            $perPage
        );

        $this->assertEquals('paginated_result', $result);
    }

    /**
     * Test pengambilan seluruh data satuan tanpa pagination.
     *
     * Skenario:
     * - Parameter pencarian (search) diberikan.
     * - Pagination dinonaktifkan.
     * - Repository memanggil method getAll.
     *
     * Ekspektasi:
     * - Service mengembalikan seluruh data satuan
     *   sesuai hasil dari repository.
     */
    public function test_get_units_without_pagination()
    {
        $search = 'meter';

        $this->unitRepository
            ->shouldReceive('getAll')
            ->once()
            ->with($search)
            ->andReturn('all_result');

        $result = $this->unitService->getUnits(
            $search,
            false
        );

        $this->assertEquals('all_result', $result);
    }

    /**
     * Test bahwa pembuatan satuan gagal
     * ketika nama satuan sudah terdaftar.
     *
     * Skenario:
     * - Repository mengembalikan true
     *   saat pengecekan nama satuan.
     * - Service mencoba membuat satuan baru
     *   dengan nama yang sudah ada.
     *
     * Ekspektasi:
     * - DomainException dilempar
     *   dengan pesan bahwa nama satuan sudah ada.
     */
    public function test_create_unit_throw_exception_if_name_exists()
    {
        $data = [
            'name' => 'm',
        ];

        $this->unitRepository
            ->shouldReceive('existsByName')
            ->once()
            ->with('m')
            ->andReturn(true);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Nama satuan sudah ada.');

        $this->unitService->createUnit($data);
    }

    /**
     * Test pembuatan data satuan baru berhasil.
     *
     * Skenario:
     * - Nama satuan belum terdaftar.
     * - Database transaction dijalankan.
     * - Code generator menghasilkan kode satuan unik.
     * - Repository membuat data satuan baru
     *   dengan kode yang telah dihasilkan.
     *
     * Ekspektasi:
     * - Data satuan berhasil dibuat.
     * - Kode satuan otomatis tersimpan dalam data.
     * - Method createUnit mengembalikan
     *   instance satuan hasil repository.
     */
    public function test_create_unit_successfully()
    {
        $data = [
            'name' => 'm',
            'category' => 'Panjang',
        ];

        DB::shouldReceive('transaction')
            ->once()
            ->andReturnUsing(function ($callback) {
                return $callback();
            });

        $this->unitRepository
            ->shouldReceive('existsByName')
            ->once()
            ->with('m')
            ->andReturn(false);

        $this->codeGenerator
            ->shouldReceive('generate')
            ->once()
            ->with(
                MasterUnit::class,
                'code',
                'SAT'
            )
            ->andReturn('SAT001');

        $unitMock = Mockery::mock(MasterUnit::class);

        $this->unitRepository
            ->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($arg) {
                return isset($arg['code'])
                    && $arg['code'] === 'SAT001';
            }))
            ->andReturn($unitMock);

        $result = $this->unitService->createUnit($data);

        $this->assertEquals($unitMock, $result);
    }

    /**
     * Test bahwa proses update satuan gagal
     * ketika nama satuan duplikat dengan data lain.
     *
     * Skenario:
     * - Data satuan ditemukan berdasarkan ID.
     * - Repository mengembalikan true
     *   saat pengecekan nama duplikat.
     *
     * Ekspektasi:
     * - DomainException dilempar
     *   dengan pesan bahwa nama satuan sudah ada.
     */
    public function test_update_unit_throw_exception_if_name_duplicate()
    {
        $unitId = '550e8400-e29b-41d4-a716-446655440005';

        $data = [
            'name' => 'm',
        ];

        $unitMock = Mockery::mock(MasterUnit::class);

        $this->unitRepository
            ->shouldReceive('find')
            ->once()
            ->with($unitId)
            ->andReturn($unitMock);

        $this->unitRepository
            ->shouldReceive('existsByNameExcept')
            ->once()
            ->with($unitId, 'm')
            ->andReturn(true);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Nama satuan sudah ada.');

        $this->unitService->updateUnit($unitId, $data);
    }

    /**
     * Test pembaruan data satuan berhasil.
     *
     * Skenario:
     * - Data satuan ditemukan berdasarkan ID.
     * - Nama satuan tidak duplikat.
     * - Repository menjalankan proses update
     *   dengan data baru yang diberikan.
     *
     * Ekspektasi:
     * - Method updateUnit mengembalikan nilai true
     *   sebagai indikasi bahwa proses update berhasil.
     */
    public function test_update_unit_successfully()
    {
        $unitId = '550e8400-e29b-41d4-a716-446655440004';

        $data = [
            'name' => 'kg',
            'category' => 'Berat',
        ];

        $unitMock = Mockery::mock(MasterUnit::class);

        $this->unitRepository
            ->shouldReceive('find')
            ->once()
            ->with($unitId)
            ->andReturn($unitMock);

        $this->unitRepository
            ->shouldReceive('existsByNameExcept')
            ->once()
            ->with($unitId, 'kg')
            ->andReturn(false);

        $this->unitRepository
            ->shouldReceive('update')
            ->once()
            ->with($unitMock, $data)
            ->andReturn(true);

        $result = $this->unitService->updateUnit($unitId, $data);

        $this->assertTrue($result);
    }

    /**
     * Test penghapusan data satuan berhasil.
     *
     * Skenario:
     * - Data satuan ditemukan berdasarkan ID.
     * - Repository menjalankan proses delete
     *   terhadap data satuan tersebut.
     *
     * Ekspektasi:
     * - Method deleteUnit mengembalikan nilai true
     *   sebagai indikasi bahwa proses penghapusan berhasil.
     */
    public function test_delete_unit_successfully()
    {
        $unitId = '550e8400-e29b-41d4-a716-446655440006';

        $unit = new MasterUnit;

        $this->unitRepository
            ->shouldReceive('find')
            ->once()
            ->with($unitId)
            ->andReturn($unit);

        $this->unitRepository
            ->shouldReceive('delete')
            ->once()
            ->with($unit)
            ->andReturn(true);

        $result = $this->unitService->deleteUnit($unitId);

        $this->assertTrue($result);
    }
}
