import { useState } from "react";

import usePermission from "@/hooks/usePermission";
import { useDelete } from "@/hooks/useDelete";

import { AhspToolTableProps, AhspTool } from "@/types/ahsp";

import Button from "@/Components/ui/button/Button";
import {
    Table,
    TableBody,
    TableCell,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";
import { ModalAhspTool } from "../modal/ModalTool";

import { formatCurrency } from "@/utils/formatCurrrency";
import { formatDecimal } from "@/utils/formatDecimal";

import { LiaToolsSolid } from "react-icons/lia";
import { LuPlus, LuPencil, LuTrash2 } from "react-icons/lu";

export const ToolTable = ({
    ahspId,
    tools,
    toolOptions,
}: AhspToolTableProps) => {
    const { can } = usePermission();

    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedTool, setSelectedTool] = useState<AhspTool | undefined>();

    const { handleDelete, deletingId } = useDelete({
        routeName: "ahsp.tool.destroy",
        confirmTitle: "Hapus Alat?",
        successMessage: "Alat berhasil dihapus",
        errorMessage: "Gagal menghapus alat",
    });

    return (
        <div className="flex flex-col gap-4 py-2 mt-4">
            <ModalAhspTool
                isOpen={isModalOpen}
                onClose={() => {
                    setIsModalOpen(false);
                    setSelectedTool(undefined);
                }}
                ahspId={ahspId}
                tool={selectedTool}
                toolOptions={toolOptions}
            />

            <div className="flex gap-2 justify-between items-center">
                <div className="flex gap-2 items-center">
                    <LiaToolsSolid size={24} className="text-blue-700" />
                    <p className="font-semibold">Alat</p>
                </div>
                {can("ahsp.create") && (
                    <Button
                        variant="ghost"
                        startIcon={<LuPlus size={18} />}
                        onClick={() => setIsModalOpen(true)}
                    >
                        Tambah
                    </Button>
                )}
            </div>
            {tools.length === 0 ? (
                <p className="text-gray-500 -mt-1">Belum ada komponen alat</p>
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
                                        Nama Alat
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
                                        Koefisien
                                    </TableCell>

                                    <TableCell
                                        isHeader
                                        className="px-6 py-3 text-sm font-semibold text-gray-900 text-end whitespace-nowrap"
                                    >
                                        Harga
                                    </TableCell>

                                    <TableCell
                                        isHeader
                                        className="px-6 py-3 text-sm font-semibold text-gray-900 text-end whitespace-nowrap"
                                    >
                                        Jumlah
                                    </TableCell>

                                    {can("ahsp.create") && (
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
                            {tools.map((item) => {
                                return (
                                    <TableBody key={item.id} className="divide-y divide-gray-100 border-b">
                                        <TableRow className="hover:bg-gray-50 transition">
                                            {/* Name */}
                                            <TableCell className="px-6 py-4 text-sm font-medium text-gray-800 whitespace-nowrap">
                                                {item.master_tool.name}
                                            </TableCell>

                                            {/* Unit */}
                                            <TableCell className="px-6 py-4 text-sm font-medium text-end text-gray-800 whitespace-nowrap">
                                                {item.master_tool.unit}
                                            </TableCell>

                                            {/* Koefisien */}
                                            <TableCell className="px-6 py-4 text-sm font-medium text-end text-gray-800 whitespace-nowrap">
                                                {formatDecimal(item.coefficient)}
                                            </TableCell>

                                            {/* Price */}
                                            <TableCell className="px-6 py-4 text-sm font-medium text-end text-gray-800 whitespace-nowrap">
                                                {formatCurrency(item.master_tool.price)}
                                            </TableCell>

                                            {/* Quantity */}
                                            <TableCell className="px-6 py-4 text-sm font-medium text-end text-gray-800 whitespace-nowrap">
                                                {formatCurrency(item.coefficient * item.master_tool.price)}
                                            </TableCell>

                                            {/* Action */}
                                            {can("ahsp.edit") && can("ahsp.delete") && (
                                                <TableCell className="px-6 py-4 text-sm font-medium text-end text-gray-800 whitespace-nowrap">
                                                    <div className="flex justify-end gap-1">
                                                        {/* Edit */}
                                                        <Button
                                                            size="icon"
                                                            variant="edit"
                                                            onClick={() => {
                                                                setIsModalOpen(true);
                                                                setSelectedTool(item);
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
        </div>
    )
}
