<?php

namespace App\Modules\Rab\Services;

use DomainException;
use App\Modules\Rab\Repositories\RabRepository;
use Illuminate\Support\Facades\Auth;

class RabService
{
    /**
     * Inisialisasi service dengan dependency repository.
     */
    public function __construct(
        protected RabRepository $rabRepository
    ) {}

    /**
     * Generate data RAB berdasarkan project.
     *
     * Proses:
     * - Mengambil data project beserta relasi (TOS, AHSP, dll)
     * - Menghitung total material, upah, alat, dan operasional
     * - Menyusun detail per pekerjaan (TOS)
     * - Membuat rekap global (material, upah, alat)
     * - Menyusun summary total keseluruhan
     * - Mengambil histori komentar dan approver
     *
     * Catatan:
     * - Jika TOS sudah di-lock, maka menggunakan snapshot
     * - Jika belum, maka dihitung dari AHSP
     */
    public function generate(string $projectId)
    {
        $project = $this->rabRepository->getProjectWithRelations($projectId);
        $operationalCosts = $project->operationalCosts ?? collect();

        $operationalTotal = 0;

        $operational = $operationalCosts->map(function ($item) use (&$operationalTotal) {

            $total = $item->volume * $item->unit_price;

            $operationalTotal += $total;

            return [
                'id' => $item->id,
                'name' => $item->name,
                'unit' => $item->unit,
                'volume' => $item->volume,
                'unit_price' => $item->unit_price,
                'total' => $total,
            ];
        });

        $tosList = $project->takeOffSheets;

        $summary = [
            'material_total' => 0,
            'wage_total' => 0,
            'tool_total' => 0,
            'operational_total' => 0,
            'grand_total' => 0,
        ];

        $detail = [];
        $recapMaterial = [];
        $recapWage = [];
        $recapTool = [];

        foreach ($tosList as $tos) {

            $volume = $tos->volume;

            $materialTotal = 0;
            $wageTotal = 0;
            $toolTotal = 0;

            $materials = [];
            $wages = [];
            $tools = [];

            if ($tos->isLocked() && $tos->locked_snapshot) {

                $snapshot = $tos->locked_snapshot;

                $materialTotal = $snapshot['material_total'];
                $wageTotal = $snapshot['wage_total'];
                $toolTotal = $snapshot['tool_total'];

                $materials = $snapshot['materials'];
                $wages = $snapshot['wages'];
                $tools = $snapshot['tools'];

                foreach ($materials as $item) {
                    $key = $item['id'];

                    if (! isset($recapMaterial[$key])) {
                        $recapMaterial[$key] = [
                            'name' => $item['name'],
                            'unit' => $item['unit'],
                            'quantity' => 0,
                            'price' => $item['price'],
                            'total' => 0,
                        ];
                    }

                    $recapMaterial[$key]['quantity'] += $item['qty'];
                }

                foreach ($wages as $item) {
                    $key = $item['id'];

                    if (! isset($recapWage[$key])) {
                        $recapWage[$key] = [
                            'name' => $item['name'],
                            'unit' => $item['unit'],
                            'quantity' => 0,
                            'price' => $item['price'],
                            'total' => 0,
                        ];
                    }

                    $recapWage[$key]['quantity'] += $item['qty'];
                }

                foreach ($tools as $item) {
                    $key = $item['id'];

                    if (! isset($recapTool[$key])) {
                        $recapTool[$key] = [
                            'name' => $item['name'],
                            'unit' => $item['unit'],
                            'quantity' => 0,
                            'price' => $item['price'],
                            'total' => 0,
                        ];
                    }

                    $recapTool[$key]['quantity'] += $item['qty'];
                }
            } else {

                $ahsp = $tos->ahsp;
                if (! $ahsp) {
                    continue;
                }

                // Material
                foreach ($ahsp->ahspComponentMaterials as $item) {
                    $coef = $item->coefficient;
                    $qty = $coef * $volume;
                    $price = $item->masterMaterial->price ?? 0;
                    $total = $qty * $price;

                    $materialTotal += $total;

                    $key = $item->material_id;

                    if (! isset($recapMaterial[$key])) {
                        $recapMaterial[$key] = [
                            'name' => $item->masterMaterial->name,
                            'unit' => $item->masterMaterial->unit,
                            'quantity' => 0,
                            'price' => $price,
                            'total' => 0,
                        ];
                    }

                    $recapMaterial[$key]['quantity'] += $qty;

                    $materials[] = [
                        'id' => $item->material_id,
                        'name' => $item->masterMaterial->name,
                        'unit' => $item->masterMaterial->unit,
                        'coefficient' => $coef,
                        'volume_x_coef' => $qty,
                        'qty' => $qty,
                        'price' => $price,
                        'total' => $total,
                    ];
                }

                // Wage
                foreach ($ahsp->ahspComponentWages as $item) {
                    $coef = $item->coefficient;
                    $qty = $coef * $volume;
                    $price = $item->masterWage->price ?? 0;
                    $total = $qty * $price;

                    $wageTotal += $total;

                    $key = $item->wage_id;

                    if (! isset($recapWage[$key])) {
                        $recapWage[$key] = [
                            'name' => $item->masterWage->position,
                            'unit' => $item->masterWage->unit,
                            'quantity' => 0,
                            'price' => $price,
                            'total' => 0,
                        ];
                    }

                    $recapWage[$key]['quantity'] += $qty;

                    $wages[] = [
                        'id' => $item->wage_id,
                        'name' => $item->masterWage->position,
                        'unit' => $item->masterWage->unit,
                        'coefficient' => $coef,
                        'volume_x_coef' => $qty,
                        'qty' => $qty,
                        'price' => $price,
                        'total' => $total,
                    ];
                }

                // Tool
                foreach ($ahsp->ahspComponentTools as $item) {
                    $coef = $item->coefficient;
                    $qty = $coef * $volume;
                    $price = $item->masterTool->price ?? 0;
                    $total = $qty * $price;

                    $toolTotal += $total;

                    $key = $item->tool_id;

                    if (! isset($recapTool[$key])) {
                        $recapTool[$key] = [
                            'name' => $item->masterTool->name,
                            'unit' => $item->masterTool->unit,
                            'quantity' => 0,
                            'price' => $price,
                            'total' => 0,
                        ];
                    }

                    $recapTool[$key]['quantity'] += $qty;

                    $tools[] = [
                        'id' => $item->tool_id,
                        'name' => $item->masterTool->name,
                        'unit' => $item->masterTool->unit,
                        'coefficient' => $coef,
                        'volume_x_coef' => $qty,
                        'qty' => $qty,
                        'price' => $price,
                        'total' => $total,
                    ];
                }
            }

            // subtotal (both case)
            $subtotal = $materialTotal + $wageTotal + $toolTotal;

            $summary['material_total'] += $materialTotal;
            $summary['wage_total'] += $wageTotal;
            $summary['tool_total'] += $toolTotal;

            $detail[] = [
                'id' => $tos->id,
                'category' => $tos->workerCategory?->name,
                'work_code' => $tos->ahsp?->work_code,
                'work_name' => $tos->work_name,
                'volume' => $volume,
                'unit' => $tos->unit,

                'material_total' => $materialTotal,
                'wage_total' => $wageTotal,
                'tool_total' => $toolTotal,
                'subtotal' => $subtotal,

                'materials' => $materials,
                'wages' => $wages,
                'tools' => $tools,
            ];
        }

        // Round quantity
        foreach ($recapMaterial as &$item) {
            $item['quantity'] = ceil($item['quantity']);
            $item['total'] = $item['quantity'] * $item['price'];
        }

        foreach ($recapWage as &$item) {
            $item['quantity'] = ceil($item['quantity']);
            $item['total'] = $item['quantity'] * $item['price'];
        }

        foreach ($recapTool as &$item) {
            $item['quantity'] = ceil($item['quantity']);
            $item['total'] = $item['quantity'] * $item['price'];
        }

        unset($item);

        // Summary
        $summary['material_total'] = array_sum(array_column($recapMaterial, 'total'));
        $summary['wage_total'] = array_sum(array_column($recapWage, 'total'));
        $summary['tool_total'] = array_sum(array_column($recapTool, 'total'));
        $summary['operational_total'] = $operationalTotal;

        $summary['grand_total'] =
            $summary['material_total'] +
            $summary['wage_total'] +
            $summary['tool_total'] +
            $summary['operational_total'];

        $comments = $this->rabRepository->getComments($projectId);
        $history = $comments->map(function ($item) {
            return [
                'id' => $item->id,
                'user' => $item->user?->name,
                'role' => $item->user?->role,
                'action' => $item->action,
                'comment' => $item->comment,
                'date' => $item->created_at,
            ];
        });

        $approver = $this->rabRepository->getApprover();

        return [
            'project' => $project,
            'summary' => $summary,
            'detail' => $detail,
            'recap_material' => array_values($recapMaterial),
            'recap_wage' => array_values($recapWage),
            'recap_tool' => array_values($recapTool),
            'operational' => $operational,
            'history' => $history,

            'approver' => $approver,
            'chairman' => $project->chairman,
        ];
    }

    /**
     * Menangani aksi workflow RAB (send, review, approve, revision).
     *
     * Alur:
     * - Validasi role dan status saat ini
     * - Menentukan status baru
     * - Update status RAB
     * - Lock harga jika sudah approved
     * - Simpan komentar histori
     *
     * Role:
     * - planner
     * - reviewer
     * - approver
     */
    public function handleAction(array $data)
    {
        $project = $this->rabRepository->getProjectWithRelations($data['project_id']);

        $role = Auth::user()->role;
        $status = $project->rab_status;
        $action = $data['action'];

        match ($role) {
            'planner' => $this->handlePlanner($project, $action, $status),
            'reviewer' => $this->handleReviewer($action, $status),
            'approver' => $this->handleApprover($action, $status),
            default => throw new DomainException('Role tidak valid')
        };

        $newStatus = $this->resolveStatus($role, $action);
        $this->rabRepository->updateStatus($project->id, $newStatus);

        if ($newStatus === 'approved') {
            $this->lockPrices($project);
        }

        $this->rabRepository->storeComment([
            'project_id' => $project->id,
            'user_id' => Auth::id(),
            'action' => $action,
            'comment' => $this->resolveDefaultComment($action, $data['comment'] ?? null),
        ]);
    }

    /**
     * Validasi aksi oleh planner.
     *
     * Aturan:
     * - Tidak bisa aksi jika sudah approved
     * - Hanya bisa kirim saat status draft/revision
     */
    private function handlePlanner($project, $action, $status)
    {
        if ($status === 'approved') {
            throw new DomainException('RAB sudah disetujui');
        }

        if ($action === 'send') {

            if (! in_array($status, ['draft', 'revision'])) {
                throw new DomainException('RAB tidak bisa dikirim');
            }

            if ($project->takeOffSheets->isEmpty()) {
                throw new DomainException('Item RAB belum ada');
            }

            foreach ($project->takeOffSheets as $tos) {

                if (! $tos->ahsp) {
                    throw new DomainException(
                        "Pekerjaan {$tos->work_name} belum memiliki AHSP"
                    );
                }

                $ahsp = $tos->ahsp;

                if (
                    $ahsp->ahspComponentMaterials->isEmpty() &&
                    $ahsp->ahspComponentWages->isEmpty() &&
                    $ahsp->ahspComponentTools->isEmpty()
                ) {
                    throw new DomainException(
                        "AHSP pada pekerjaan {$tos->work_name} belum memiliki item"
                    );
                }
            }
        }
    }

    /**
     * Validasi aksi oleh reviewer.
     *
     * Aturan:
     * - Hanya bisa saat status submitted/revision
     * - Aksi yang diperbolehkan: revision, forward
     */
    private function handleReviewer($action, $status)
    {
        if (! in_array($status, ['submitted', 'revision'])) {
            throw new DomainException('Belum bisa direview');
        }

        if (! in_array($action, ['revision', 'forward'])) {
            throw new DomainException('Aksi tidak valid');
        }
    }

    /**
     * Validasi aksi oleh approver.
     *
     * Aturan:
     * - Hanya bisa saat status reviewed
     * - Aksi yang diperbolehkan: approve, revision
     */
    private function handleApprover($action, $status)
    {
        if (! in_array($status, ['reviewed'])) {
            throw new DomainException('Belum bisa diapprove');
        }

        if (! in_array($action, ['revision', 'approve'])) {
            throw new DomainException('Aksi tidak valid');
        }
    }

    /**
     * Menentukan status baru berdasarkan role dan aksi.
     *
     * Mapping:
     * - planner + send → submitted
     * - reviewer + forward → reviewed
     * - reviewer + revision → revision
     * - approver + approve → approved
     * - approver + revision → revision
     */
    private function resolveStatus($role, $action)
    {
        return match (true) {
            $role === 'planner' && $action === 'send' => 'submitted',

            $role === 'reviewer' && $action === 'forward' => 'reviewed',
            $role === 'reviewer' && $action === 'revision' => 'revision',

            $role === 'approver' && $action === 'approve' => 'approved',
            $role === 'approver' && $action === 'revision' => 'revision',

            default => throw new DomainException('Transisi tidak valid')
        };
    }

    /**
     * Menentukan default komentar jika user tidak mengisi.
     */
    private function resolveDefaultComment($action, $comment)
    {
        if (! empty($comment)) {
            return $comment;
        }

        return match ($action) {
            'send' => 'RAB dikirim untuk direview',
            'approve' => 'RAB disetujui',
            default => null,
        };
    }

    /**
     * Mengunci harga pada setiap TOS ketika RAB disetujui.
     *
     * Proses:
     * - Mengambil data dari AHSP
     * - Menghitung ulang material, upah, alat
     * - Menyimpan snapshot (JSON)
     * - Menyimpan unit price terkunci
     *
     * Catatan:
     * - Data snapshot digunakan untuk menjaga konsistensi harga
     *   meskipun harga master berubah di masa depan
     */
    private function lockPrices($project)
    {
        foreach ($project->takeOffSheets as $tos) {

            if ($tos->isLocked()) {
                continue;
            }

            $ahsp = $tos->ahsp;
            if (! $ahsp) {
                continue;
            }

            $volume = $tos->volume;

            $materials = [];
            $wages = [];
            $tools = [];

            $materialTotal = 0;
            $wageTotal = 0;
            $toolTotal = 0;

            // MATERIAL
            foreach ($ahsp->ahspComponentMaterials as $item) {
                $coef = $item->coefficient;
                $qty = $coef * $volume;
                $price = $item->masterMaterial->price ?? 0;
                $total = $qty * $price;

                $materialTotal += $total;

                $materials[] = [
                    'id' => $item->material_id,
                    'name' => $item->masterMaterial->name,
                    'unit' => $item->masterMaterial->unit,
                    'coefficient' => $coef,
                    'qty' => $qty,
                    'price' => $price,
                    'total' => $total,
                ];
            }

            // WAGE
            foreach ($ahsp->ahspComponentWages as $item) {
                $coef = $item->coefficient;
                $qty = $coef * $volume;
                $price = $item->masterWage->price ?? 0;
                $total = $qty * $price;

                $wageTotal += $total;

                $wages[] = [
                    'id' => $item->wage_id,
                    'name' => $item->masterWage->position,
                    'unit' => $item->masterWage->unit,
                    'coefficient' => $coef,
                    'qty' => $qty,
                    'price' => $price,
                    'total' => $total,
                ];
            }

            // TOOL
            foreach ($ahsp->ahspComponentTools as $item) {
                $coef = $item->coefficient;
                $qty = $coef * $volume;
                $price = $item->masterTool->price ?? 0;
                $total = $qty * $price;

                $toolTotal += $total;

                $tools[] = [
                    'id' => $item->tool_id,
                    'name' => $item->masterTool->name,
                    'unit' => $item->masterTool->unit,
                    'coefficient' => $coef,
                    'qty' => $qty,
                    'price' => $price,
                    'total' => $total,
                ];
            }

            $subtotal = $materialTotal + $wageTotal + $toolTotal;

            $tos->update([
                'locked_unit_price' => $subtotal / ($volume ?: 1),
                'locked_snapshot' => [
                    'materials' => $materials,
                    'wages' => $wages,
                    'tools' => $tools,
                    'material_total' => $materialTotal,
                    'wage_total' => $wageTotal,
                    'tool_total' => $toolTotal,
                    'subtotal' => $subtotal,
                ],
                'locked_at' => now(),
            ]);
        }
    }
}
