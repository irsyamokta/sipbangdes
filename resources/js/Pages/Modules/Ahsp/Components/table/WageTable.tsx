import { useState } from "react";

import usePermission from "@/hooks/usePermission";
import { useDelete } from "@/hooks/useDelete";

import { AhspWageTableProps, AhspWage } from "@/types/ahsp";

import Button from "@/Components/ui/button/Button";
import {
    Table,
    TableBody,
    TableCell,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";
import AhspWageModal from "../modal/WageModal";

import { formatCurrency } from "@/utils/formatCurrrency";
import { formatDecimal } from "@/utils/formatDecimal";
import { capitalizeEachWord } from "@/utils/capitalize";

import { LuPlus, LuPencil, LuTrash2, LuWallet } from "react-icons/lu";

const WageTable = ({
    ahspId,
    wages,
    wageOptions,
}: AhspWageTableProps) => {
    const { can } = usePermission();

    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedWage, setSelectedWage] = useState<AhspWage | undefined>();

    const { handleDelete, deletingId } = useDelete({
        routeName: "ahsp.wage.destroy",
        confirmTitle: "Hapus upah?",
        successMessage: "Upah berhasil dihapus",
        errorMessage: "Gagal menghapus upah",
    });

    return (
        <div className="flex flex-col gap-4 py-2 mt-4">
            {/* Modal */}
            <AhspWageModal
                isOpen={isModalOpen}
                onClose={() => {
                    setIsModalOpen(false);
                    setSelectedWage(undefined);
                }}
                ahspId={ahspId}
                wage={selectedWage}
                wageOptions={wageOptions}
            />

            <div className="flex gap-2 justify-between items-center">
                {/* Header */}
                <div className="flex gap-2 items-center">
                    <LuWallet size={20} className="text-warning-700" />
                    <p className="font-semibold">Upah</p>
                </div>

                {/* Button */}
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
            {wages.length === 0 ? (
                <p className="text-gray-500 -mt-1">Belum ada komponen upah</p>
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
                                        Jabatan
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

                                    {can("ahsp.create") && (
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
                            {wages.map((item) => {
                                return (
                                    <TableBody key={item.id} className="divide-y divide-gray-100 border-b">
                                        <TableRow className="hover:bg-gray-50 transition">
                                            {/* Position */}
                                            <TableCell className="px-6 py-4 text-sm font-medium text-start text-gray-800 whitespace-nowrap">
                                                {capitalizeEachWord(item.master_wage.position)}
                                            </TableCell>

                                            {/* Unit */}
                                            <TableCell className="px-6 py-4 text-sm font-medium text-start text-gray-800 whitespace-nowrap">
                                                {item.master_wage.unit}
                                            </TableCell>

                                            {/* Coefficient */}
                                            <TableCell className="px-6 py-4 text-sm font-medium text-start text-gray-800 whitespace-nowrap">
                                                {formatDecimal(item.coefficient)}
                                            </TableCell>

                                            {/* Price */}
                                            <TableCell className="px-6 py-4 text-sm font-medium text-start text-gray-800 whitespace-nowrap">
                                                {formatCurrency(item.master_wage.price)}
                                            </TableCell>

                                            {/* Quantity */}
                                            <TableCell className="px-6 py-4 text-sm font-medium text-start text-gray-800 whitespace-nowrap">
                                                {formatCurrency(item.coefficient * item.master_wage.price)}
                                            </TableCell>

                                            {/* Action */}
                                            {can("ahsp.edit") && can("ahsp.delete") && (
                                                <TableCell className="px-6 py-4 text-sm font-medium text-center text-gray-800 whitespace-nowrap">
                                                    <div className="flex justify-center gap-1">
                                                        {/* Edit */}
                                                        <Button
                                                            size="icon"
                                                            variant="edit"
                                                            onClick={() => {
                                                                setIsModalOpen(true);
                                                                setSelectedWage(item);
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

export default WageTable;
