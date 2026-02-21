<?php

namespace App\Modules\Project\Controllers;

use Throwable;
use DomainException;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Modules\Project\Services\ProgressService;
use App\Modules\Project\Requests\ProgressStoreRequest;

class ProgressController extends Controller
{
    public function __construct(
        protected ProgressService $service
    ) {}

    public function show(string $id)
    {
        try {
            $data = $this->service->getDetail($id);

            return Inertia::render('Modules/Projects/Progress', [
                'project' => $data['project'],
                'totalProgress' => $data['totalProgress'],
            ]);
        } catch (Throwable $e) {
            return redirect()
                ->route('project.index')
                ->withErrors(['Proyek tidak ditemukan']);
        }
    }

    public function storeProgress(ProgressStoreRequest $request, string $id)
    {
        try {
            $this->service->createWithDocuments(
                data: [
                    ...$request->validated(),
                    'project_id' => $id,
                    'reported_by' => auth()->id(),
                ],
                files: $request->file('documents', [])
            );
            
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
