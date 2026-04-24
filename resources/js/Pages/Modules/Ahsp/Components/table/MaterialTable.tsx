import { useState } from "react";

import usePermission from "@/hooks/usePermission";
import { useDelete } from "@/hooks/useDelete";

import { AhspMaterialTableProps, AhspMaterial } from "@/types/ahsp";

import Button from "@/Components/ui/button/Button";
import {
    Table,
    TableBody,
    TableCell,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";
import AhspMaterialModal from "../modal/MaterialModal";

import { formatCurrency } from "@/utils/formatCurrrency";
import { formatDecimal } from "@/utils/formatDecimal";
import { capitalizeEachWord } from "@/utils/capitalize";

import { PiPackage } from "react-icons/pi";
import { LuPlus, LuPencil, LuTrash2 } from "react-icons/lu";

const MaterialTable = ({
    ahspId,
    materials,
    materialOptions,
}: AhspMaterialTableProps) => {
    const { can } = usePermission();

    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedMaterial, setSelectedMaterial] = useState<AhspMaterial | undefined>();

    const { handleDelete, deletingId } = useDelete({
        routeName: "ahsp.material.destroy",
        confirmTitle: "Hapus material?",
        successMessage: "Material berhasil dihapus",
        errorMessage: "Gagal menghapus material",
    });

    return (
        <div className="flex flex-col gap-4 py-2 mt-4">
            {/* Modal */}
            <AhspMaterialModal
                isOpen={isModalOpen}
                onClose={() => {
                    setIsModalOpen(false);
                    setSelectedMaterial(undefined);
                }}
                ahspId={ahspId}
                material={selectedMaterial}
                materialOptions={materialOptions}
            />

            <div className="flex gap-2 justify-between items-center">
                {/* Header */}
                <div className="flex gap-2 items-center">
                    <PiPackage size={24} className="text-green-700" />
                    <p className="font-semibold">Material</p>
                </div>

                {/* Button */}
                {can('ahsp.create') && (
                    <Button
                        variant="ghost"
                        startIcon={<LuPlus size={18} />}
                        onClick={() => setIsModalOpen(true)}
                    >
                        Tambah
                    </Button>
                )}
            </div>
            {materials.length === 0 ? (
                <p className="text-gray-500 -mt-1">Belum ada komponen material</p>
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
                                        Nama
                                    </TableCell>

                                    <TableCell
                                        isHeader
                                        className="px-6 py-3 text-sm font-semibold text-gray-900 text-start whitespace-nowrap"
                                    >
                                        Satuan
                                    </TableCell>

                                    <TableCell
                                        isHeader
                                        className="px-6 py-3 text-sm font-semibold text-gray-900 text-start whitespace-nowrap"
                                    >
                                        Koefisien
                                    </TableCell>

                                    <TableCell
                                        isHeader
                                        className="px-6 py-3 text-sm font-semibold text-gray-900 text-start whitespace-nowrap"
                                    >
                                        Harga
                                    </TableCell>

                                    <TableCell
                                        isHeader
                                        className="px-6 py-3 text-sm font-semibold text-gray-900 text-start whitespace-nowrap"
                                    >
                                        Jumlah
                                    </TableCell>

                                    {can('ahsp.create') && (
                                        <TableCell
                                            isHeader
                                            className="px-6 py-3 text-sm font-semibold text-gray-900 text-center whitespace-nowrap"
                                        >
                                            Aksi
                                        </TableCell>
                                    )}
                                </TableRow>
                            </TableHeader>

                            {/* Body */}
                            {materials.map((item) => {
                                return (
                                    <TableBody key={item.id} className="divide-y divide-gray-100 border-b">
                                        <TableRow className="hover:bg-gray-50 transition">
                                            {/* Name */}
                                            <TableCell className="px-6 py-4 text-sm font-medium text-start text-gray-800 whitespace-nowrap">
                                                {capitalizeEachWord(item.master_material.name)}
                                            </TableCell>

                                            {/* Unit */}
                                            <TableCell className="px-6 py-4 text-sm font-medium text-start text-gray-800 whitespace-nowrap">
                                                {item.master_material.unit}
                                            </TableCell>

                                            {/* Coefficient */}
                                            <TableCell className="px-6 py-4 text-sm font-medium text-start text-gray-800 whitespace-nowrap">
                                                {formatDecimal(item.coefficient)}
                                            </TableCell>

                                            {/* Price */}
                                            <TableCell className="px-6 py-4 text-sm font-medium text-start text-gray-800 whitespace-nowrap">
                                                {formatCurrency(item.master_material.price)}
                                            </TableCell>

                                            {/* Quantity */}
                                            <TableCell className="px-6 py-4 text-sm font-medium text-start text-gray-800 whitespace-nowrap">
                                                {formatCurrency(item.coefficient * item.master_material.price)}
                                            </TableCell>

                                            {/* Action */}
                                            {can('ahsp.edit') && can('ahsp.delete') && (
                                                <TableCell className="px-6 py-4 text-sm font-medium text-end text-gray-800 whitespace-nowrap">
                                                    <div className="flex justify-center gap-1">
                                                        {/* Edit */}
                                                        <Button
                                                            size="icon"
                                                            variant="edit"
                                                            onClick={() => {
                                                                setIsModalOpen(true);
                                                                setSelectedMaterial(item);
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

export default MaterialTable;
