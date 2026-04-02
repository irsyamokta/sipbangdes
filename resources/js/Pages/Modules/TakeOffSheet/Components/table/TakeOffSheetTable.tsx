import { useState } from "react";

import usePermission from "@/hooks/usePermission";
import { useDelete } from "@/hooks/useDelete";

import { TakeOffSheetTableProps, TakeOffSheet } from "@/types/tos";

import Button from "@/Components/ui/button/Button";
import {
    Table,
    TableBody,
    TableCell,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";
import TakeOffSheetModal from "../modal/TakeOffSheetModal";
import { EmptyTable } from "@/Components/empty/EmptyTable";
import Pagination from "@/Components/ui/pagination/Pagination";

import { formatDecimal } from "@/utils/formatDecimal";
import { capitalizeEachWord } from "@/utils/capitalize";

import { LuPencil, LuTrash2 } from "react-icons/lu";
import { PiCalculatorLight } from "react-icons/pi";

const TakeOffSheetTable = ({
    takeOffSheets,
    projectOptions,
    workerCategoryOptions,
    ahspOptions,
    unitOptions,
    last_page,
    links,
}: TakeOffSheetTableProps) => {
    const { can } = usePermission();

    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedTos, setSelectedTos] = useState<TakeOffSheet | null>(null);

    const { handleDelete, deletingId } = useDelete({
        routeName: "tos.destroy",
        confirmTitle: "Hapus Item TOS?",
        successMessage: "TOS berhasil dihapus",
        errorMessage: "Proyek sudah disetujui",
    });

    return (
        <div className="flex flex-col gap-4 py-2 mt-4">
            {/* Modal */}
            <TakeOffSheetModal
                isOpen={isModalOpen}
                onClose={() => {
                    setIsModalOpen(false);
                    setSelectedTos(null);
                }}
                takeOffSheet={selectedTos}
                projectOptions={projectOptions}
                workerCategoryOptions={workerCategoryOptions}
                ahspOptions={ahspOptions}
                unitOptions={unitOptions}
            />

            {/* Table */}
            <div className="overflow-hidden rounded-xl border border-gray-200 bg-white">
                <div className="max-w-full overflow-x-auto">
                    <Table>
                        {/* Header */}
                        <TableHeader className="bg-gray-100 border-b">
                            <TableRow>
                                <TableCell isHeader className="px-6 py-3 text-sm font-semibold text-gray-900 text-start whitespace-nowrap">
                                    Proyek
                                </TableCell>

                                <TableCell isHeader className="px-6 py-3 text-sm font-semibold text-gray-900 text-start whitespace-nowrap">
                                    Kategori
                                </TableCell>

                                <TableCell isHeader className="px-6 py-3 text-sm font-semibold text-gray-900 text-start whitespace-nowrap">
                                    Nama Pekerjaan
                                </TableCell>

                                <TableCell isHeader className="px-6 py-3 text-sm font-semibold text-gray-900 text-start whitespace-nowrap">
                                    Volume
                                </TableCell>

                                <TableCell isHeader className="px-6 py-3 text-sm font-semibold text-gray-900 text-start whitespace-nowrap">
                                    Satuan
                                </TableCell>

                                <TableCell isHeader className="px-6 py-3 text-sm font-semibold text-gray-900 text-start whitespace-nowrap">
                                    AHSP
                                </TableCell>

                                {(can('tos.edit') || can('tos.delete')) && (
                                    <TableCell isHeader className="px-6 py-3 text-sm font-semibold text-gray-900 text-center">
                                        Aksi
                                    </TableCell>
                                )}
                            </TableRow>
                        </TableHeader>

                        {/* Body */}
                        <TableBody className="divide-y divide-gray-100">
                            {takeOffSheets.length === 0 ? (
                                <EmptyTable
                                    colspan={can('tos.edit') || can('tos.delete') ? 7 : 6}
                                    description="Belum ada data Take Off Sheet"
                                />
                            ) : (
                                takeOffSheets.map((item) => (
                                    <TableRow key={item.id} className="hover:bg-gray-50 transition">
                                        {/* Project Name */}
                                        <TableCell className="px-6 py-4 text-sm font-medium text-gray-800 whitespace-nowrap">
                                            {item.project?.project_name ? capitalizeEachWord(item.project?.project_name) : "-"}
                                        </TableCell>

                                        {/* Worker Category */}
                                        <TableCell className="px-6 py-4 text-sm font-medium text-gray-800 whitespace-nowrap">
                                            {item.worker_category ? capitalizeEachWord(item.worker_category?.name ?? "") : "-"}
                                        </TableCell>

                                        {/* Work Name */}
                                        <TableCell className="px-6 py-4 text-sm font-medium text-gray-800 whitespace-nowrap">
                                            {capitalizeEachWord(item.work_name)}
                                        </TableCell>

                                        {/* Volume */}
                                        <TableCell className="px-6 py-4 text-sm font-medium text-gray-800 whitespace-nowrap">
                                            {formatDecimal(item.volume)}
                                        </TableCell>

                                        {/* Unit */}
                                        <TableCell className="px-6 py-4 text-sm font-medium text-gray-800 whitespace-nowrap">
                                            {item.unit}
                                        </TableCell>

                                        {/* AHSP */}
                                        <TableCell className="px-6 py-4 text-sm font-medium text-gray-800 whitespace-nowrap">
                                            <div className="flex items-center gap-2">
                                                <PiCalculatorLight size={18} />
                                                {item.ahsp?.work_code ?? "-"}
                                            </div>
                                        </TableCell>

                                        {/* Action */}
                                        {(can('tos.edit') || can('tos.delete')) && (
                                            <TableCell className="px-6 py-4">
                                                <div className="flex justify-center gap-1">
                                                    {can('tos.edit') && (
                                                        <Button
                                                            size="icon"
                                                            variant="edit"
                                                            onClick={() => {
                                                                setSelectedTos(item);
                                                                setIsModalOpen(true);
                                                            }}
                                                        >
                                                            <LuPencil size={18} />
                                                        </Button>
                                                    )}

                                                    {can('tos.delete') && (
                                                        <Button
                                                            size="icon"
                                                            variant="danger"
                                                            onClick={() => handleDelete(item.id)}
                                                            disabled={deletingId === item.id}
                                                        >
                                                            <LuTrash2 size={18} />
                                                        </Button>
                                                    )}
                                                </div>
                                            </TableCell>
                                        )}
                                    </TableRow>
                                ))
                            )}
                        </TableBody>
                    </Table>
                </div>

                {/* Pagination */}
                {last_page > 1 && (
                    <div className="pb-6 border-t">
                        <Pagination
                            links={links}
                            routeName="tos.index"
                        />
                    </div>
                )}
            </div>
        </div>
    );
};

export default TakeOffSheetTable;
