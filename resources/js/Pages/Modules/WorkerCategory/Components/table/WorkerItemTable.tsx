import { useState } from "react";

import usePermission from "@/hooks/usePermission";
import { useDelete } from "@/hooks/useDelete";

import { WorkerItemTableProps, WorkerItem } from "@/types/workerCategory";

import Button from "@/Components/ui/button/Button";
import {
    Table,
    TableBody,
    TableCell,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";
import { ModalWorkerItem } from "../modal/ModalWorkerItem";

import { LuPlus, LuPencil, LuTrash2 } from "react-icons/lu";
import { PiCalculatorLight } from "react-icons/pi";

export const WorkerItemTable = ({
    categoryId,
    workerItems,
    ahspOptions,
    unitOptions
}: WorkerItemTableProps) => {
    const { can } = usePermission();

    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedWorkerItem, setSelectedWorkerItem] = useState<WorkerItem | undefined>();

    const { handleDelete, deletingId } = useDelete({
        routeName: "workeritem.destroy",
        confirmTitle: "Hapus item pekerjaan?",
        successMessage: "Item pekerjaan berhasil dihapus",
        errorMessage: "Gagal menghapus item pekerjaan",
    });

    return (
        <div className="flex flex-col gap-2 py-2">
            <ModalWorkerItem
                isOpen={isModalOpen}
                onClose={() => {
                    setIsModalOpen(false)
                    setSelectedWorkerItem(undefined)
                }}
                workerItem={selectedWorkerItem}
                categoryId={categoryId}
                unitOptions={unitOptions}
                ahspOptions={ahspOptions}
            />

            {workerItems.length === 0 ? (
                <p className="text-gray-500">Belum ada item pekerjaan untuk kategori ini</p>
            ) : (
                <div className="overflow-hidden">
                    {/* Table */}
                    <div className="max-w-full overflow-x-auto">
                        <Table>
                            {/* Header */}
                            <TableHeader className="bg-gray-200">
                                <TableRow>
                                    <TableCell
                                        isHeader
                                        className="px-6 py-3 text-sm font-semibold text-gray-900 text-start whitespace-nowrap"
                                    >
                                        Nama Pekerjaan
                                    </TableCell>

                                    <TableCell
                                        isHeader
                                        className="px-6 py-3 text-sm font-semibold text-gray-900 text-end whitespace-nowrap"
                                    >
                                        Satuan
                                    </TableCell>

                                    <TableCell
                                        isHeader
                                        className="px-6 py-3 text-sm font-semibold text-gray-900 text-end whitespace-nowrap"
                                    >
                                        Referensi AHSP
                                    </TableCell>

                                    {can('workercategory.create') && (
                                        <TableCell
                                            isHeader
                                            className="px-6 py-3 text-sm font-semibold text-gray-900 text-end whitespace-nowrap"
                                        >
                                            Aksi
                                        </TableCell>
                                    )}
                                </TableRow>
                            </TableHeader>

                            {/* Body */}
                            {workerItems.map((item) => {
                                return (
                                    <TableBody key={item.id} className="divide-y divide-gray-100 border-b">
                                        <TableRow className="hover:bg-gray-50 transition">
                                            {/* Worker Name */}
                                            <TableCell className="px-6 py-4 text-sm font-medium text-gray-800 whitespace-nowrap">
                                                {item.work_name}
                                            </TableCell>

                                            {/* Unit */}
                                            <TableCell className="px-6 py-4 text-sm font-medium text-end text-gray-800 whitespace-nowrap">
                                                {item.unit}
                                            </TableCell>

                                            {/* AHSP */}
                                            <TableCell className="px-6 py-4 text-sm font-medium text-end text-gray-800 whitespace-nowrap">
                                                <div className="flex justify-end items-center gap-2">
                                                    <PiCalculatorLight size={20}/> {item.ahsp.work_code} - {item.ahsp.work_name}
                                                </div>
                                            </TableCell>

                                            {/* Action */}
                                            {can('workercategory.edit') && can('workercategory.delete') && (
                                                <TableCell className="px-6 py-4 text-sm font-medium text-end text-gray-800 whitespace-nowrap">
                                                    <div className="flex justify-end gap-1">
                                                        {/* Edit */}
                                                        <Button
                                                            size="icon"
                                                            variant="edit"
                                                            onClick={() => {
                                                                setIsModalOpen(true);
                                                                setSelectedWorkerItem(item);
                                                            }}
                                                        >
                                                            <LuPencil size={18} />
                                                        </Button>

                                                        {/* Delete */}
                                                        <Button
                                                            size="icon"
                                                            variant="danger"
                                                            onClick={() => handleDelete(item.id)}
                                                            disabled={deletingId === item.id}
                                                            className="disabled:opacity-50"
                                                        >
                                                            {deletingId === item.id ? (
                                                                <LuTrash2 size={18} className="animate-spin" />
                                                            ) : (
                                                                <LuTrash2 size={18} />
                                                            )}
                                                        </Button>
                                                    </div>
                                                </TableCell>
                                            )}
                                        </TableRow>
                                    </TableBody>
                                );
                            })}
                        </Table>
                    </div>
                </div>
            )}
            <div>
                {can('workercategory.create') && (
                    <Button
                        variant="ghost"
                        startIcon={<LuPlus size={18} />}
                        onClick={() => setIsModalOpen(true)}
                        className="border border-gray-300 mt-2"
                    >
                        Tambah Item
                    </Button>
                )}
            </div>
        </div>
    )
}
