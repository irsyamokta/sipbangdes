<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Mockery;

use Illuminate\Support\Facades\DB;

use App\Models\MasterTool;
use App\Modules\Tool\Services\ToolService;
use App\Modules\Tool\Repositories\ToolRepository;
use App\Services\CodeGeneratorService;

class ToolServiceTest extends TestCase
{
    protected $toolRepository;
    protected $codeGenerator;
    protected $toolService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->toolRepository =
            Mockery::mock(ToolRepository::class);

        $this->codeGenerator =
            Mockery::mock(CodeGeneratorService::class);

        $this->toolService = new ToolService(
            $this->toolRepository,
            $this->codeGenerator
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_tools_with_pagination()
    {
        $search = 'bor';
        $perPage = 10;

        $this->toolRepository
            ->shouldReceive('getPaginated')
            ->once()
            ->with($search, $perPage)
            ->andReturn('paginated_result');

        $result = $this->toolService->getTools(
            $search,
            true,
            $perPage
        );

        $this->assertEquals(
            'paginated_result',
            $result
        );
    }

    public function test_get_tools_without_pagination()
    {
        $search = 'bor';

        $this->toolRepository
            ->shouldReceive('getAll')
            ->once()
            ->with($search)
            ->andReturn('all_result');

        $result = $this->toolService->getTools(
            $search,
            false
        );

        $this->assertEquals(
            'all_result',
            $result
        );
    }

    public function test_create_tool_successfully()
    {
        $data = [
            'name' => 'Bor Tangan',
            'unit' => 'hari',
            'price' => 85000
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
                MasterTool::class,
                'code',
                'TL'
            )
            ->andReturn('TL001');

        $toolMock =
            Mockery::mock(MasterTool::class);

        $this->toolRepository
            ->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($arg) {
                return isset($arg['code'])
                    && $arg['code'] === 'TL001';
            }))
            ->andReturn($toolMock);

        $result = $this->toolService
            ->createTool($data);

        $this->assertEquals(
            $toolMock,
            $result
        );
    }

    public function test_update_tool_successfully()
    {
        $toolId = '550e8400-e29b-41d4-a716-446655440020';

        $data = [
            'name' => 'Bor Listrik',
            'unit' => 'hari',
            'price' => 95000
        ];

        $toolMock =
            Mockery::mock(MasterTool::class);

        $this->toolRepository
            ->shouldReceive('find')
            ->once()
            ->with($toolId)
            ->andReturn($toolMock);

        $this->toolRepository
            ->shouldReceive('update')
            ->once()
            ->with($toolMock, $data)
            ->andReturn(true);

        $result = $this->toolService
            ->updateTool($toolId, $data);

        $this->assertTrue($result);
    }

    public function test_delete_tool_successfully()
    {
        $toolId = '550e8400-e29b-41d4-a716-446655440021';

        $tool = new MasterTool();

        $this->toolRepository
            ->shouldReceive('find')
            ->once()
            ->with($toolId)
            ->andReturn($tool);

        $this->toolRepository
            ->shouldReceive('delete')
            ->once()
            ->with($tool)
            ->andReturn(true);

        $result = $this->toolService
            ->deleteTool($toolId);

        $this->assertTrue($result);
    }
}