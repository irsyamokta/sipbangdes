<?php

namespace App\Modules\Project\Controllers;

use Throwable;
use DomainException;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Project\Services\ProjectService;
use App\Modules\Project\Requests\ProjectStoreRequest;
use App\Modules\Project\Requests\ProjectUpdateRequest;

class ProjectController extends Controller
{
    public function __construct(
        protected ProjectService $service
    ) {}

    public function index(Request $request)
    {
        $projects = $this->service->getProjects($request->search, $request->year);

        return Inertia::render('Modules/Projects/Index', [
            'projects' => $projects,
            'filters' => $request->only(['search', 'year']),
        ]);
    }

    public function store(ProjectStoreRequest $request)
    {
        try {
            $this->service->createProject($request->validated());

            return back();
        } catch (DomainException $e) {
            return back()->withErrors([
                $e->getMessage()
            ]);
        } catch (Throwable $e) {
            return back()->withErrors([
                'Terjadi kesalahan, silahkan coba lagi'
            ]);
        }
    }

    public function update(ProjectUpdateRequest $request, $id)
    {
        try {
            $this->service->updateProject($id, $request->validated());

            return back();
        } catch (DomainException $e) {
            return back()->withErrors([
                $e->getMessage()
            ]);
        } catch (Throwable $e) {
            return back()->withErrors([
                'Terjadi kesalahan, silahkan coba lagi'
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $this->service->deleteProject($id);

            return back();
        } catch (DomainException $e) {
            return back()->withErrors([
                $e->getMessage()
            ]);
        } catch (Throwable $e) {
            return back()->withErrors([
                'Terjadi kesalahan, silahkan coba lagi'
            ]);
        }
    }
}
