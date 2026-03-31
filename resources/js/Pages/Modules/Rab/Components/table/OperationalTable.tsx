import { useState } from "react";

import usePermission from "@/hooks/usePermission";
import { useDelete } from "@/hooks/useDelete";

import { RabOperational, OperationalTableProps } from "@/types/rab";

import {
    Table,
    TableBody,
    TableCell,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";
import Button from "@/Components/ui/button/Button";
import { EmptyTable } from "@/Components/empty/EmptyTable";
import { OperationalCostModal } from "../modal/OperationalCostModal";

import { formatCurrency } from "@/utils/formatCurrrency";
import { formatDecimal } from "@/utils/formatDecimal";
import { capitalizeEachWord } from "@/utils/capitalize";

import { GrMoney } from "react-icons/gr";
import { LuPlus, LuPencil, LuTrash2 } from "react-icons/lu";

export const OperationalTable = ({
    project_id,
    rab_status,
    operationals = [],
    unitOptions
}: OperationalTableProps) => {
    const { can } = usePermission();

    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedData, setSelectedData] = useState<RabOperational | null>(null);

    const totalOperational = operationals?.reduce(
        (sum, item) => sum + item.total,
        0
    );

    const handleCreate = () => {
        setSelectedData(null);
        setIsModalOpen(true);
    };

    const handleEdit = (item: RabOperational) => {
        setSelectedData(item);
        setIsModalOpen(true);
    };

    const { handleDelete, deletingId } = useDelete({
        routeName: "operational.destroy",
        confirmTitle: "Hapus biaya operasional?",
        successMessage: "Biaya operasional berhasil dihapus",
        errorMessage: "Gagal menghapus biaya operasional",
    });

    return (
        <div className="flex flex-col gap-4 py-2 mt-4">
            {/* Modal */}
            <OperationalCostModal
                isOpen={isModalOpen}
                onClose={() => setIsModalOpen(false)}
                operational={selectedData}
                projectId={project_id}
                unitOptions={unitOptions}
            />

            {/* Card */}
            <div className="overflow-hidden rounded-xl bg-white border border-gray-300">

                {/* Header */}
                <div className="flex gap-2 items-center justify-between bg-yellow-100 px-4 py-3 border-b border-gray-300">
                    <div className="flex items-center gap-2">
                        <GrMoney size={20} className="text-yellow-700" />
                        <p className="font-semibold text-gray-800">
                            Biaya Operasional
                        </p>
                    </div>
                    {
                        can("rab.create") && rab_status !== "approved" && (
                            <Button
                                variant="primary"
                                startIcon={<LuPlus size={18} />}
                                onClick={handleCreate}
                            >
                                Tambah
                            </Button>
                        )
                    }
                </div>

                {/* Table */}
                <div className="max-w-full overflow-x-auto">
                    <Table>
                        {/* Header */}
                        <TableHeader className="bg-gray-200 border-b">
                            <TableRow>
                                <TableCell isHeader className="px-6 py-3 text-xs text-start text-gray-900 whitespace-nowrap">
                                    Nama Biaya
                                </TableCell>

                                <TableCell isHeader className="px-6 py-3 text-xs text-end text-gray-900">
                                    Satuan
                                </TableCell>

                                <TableCell isHeader className="px-6 py-3 text-xs text-end text-gray-900 whitespace-nowrap">
                                    Kebutuhan
                                </TableCell>

                                <TableCell isHeader className="px-6 py-3 text-xs text-end text-gray-900 whitespace-nowrap">
                                    Harga Satuan
                                </TableCell>

                                <TableCell isHeader className="px-6 py-3 text-xs text-end text-gray-900 whitespace-nowrap">
                                    Total Harga
                                </TableCell>

                                {can("rab.create") && rab_status !== "approved" && (
                                    <TableCell isHeader className="px-6 py-3 text-xs text-end text-gray-900 whitespace-nowrap">
                                        Aksi
                                    </TableCell>
                                )}
                            </TableRow>
                        </TableHeader>

                        {/* Body */}
                        <TableBody className="divide-y divide-gray-100">
                            {operationals.length === 0 ? (
                                <EmptyTable
                                    colspan={can("rab.create") ? 6 : 5}
                                    description="Belum ada biaya operasional"
                                />
                            ) : (
                                operationals.map((item, index) => (
                                    <TableRow
                                        key={`${item.name}-${index}`}
                                        className="hover:bg-gray-50 transition"
                                    >
                                        {/* Nama */}
                                        <TableCell className="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                                            {capitalizeEachWord(item.name)}
                                        </TableCell>

                                        {/* Satuan */}
                                        <TableCell className="px-6 py-4 text-sm text-end text-gray-900">
                                            {item.unit}
                                        </TableCell>

                                        {/* Kebutuhan */}
                                        <TableCell className="px-6 py-4 text-sm text-end text-gray-900 whitespace-nowrap">
                                            {formatDecimal(item.volume)}
                                        </TableCell>

                                        {/* Harga */}
                                        <TableCell className="px-6 py-4 text-sm text-end text-gray-900 whitespace-nowrap">
                                            {formatCurrency(item.unit_price)}
                                        </TableCell>

                                        {/* Total */}
                                        <TableCell className="px-6 py-4 text-sm text-end text-gray-900 font-semibold whitespace-nowrap">
                                            {formatCurrency(item.total)}
                                        </TableCell>

                                        {/* Action */}
                                        {can('rab.create') && rab_status !== "approved" && (
                                            <TableCell className="px-6 py-4 text-sm font-medium text-end text-gray-800 whitespace-nowrap">
                                                <div className="flex justify-end gap-1">
                                                    {/* Edit */}
                                                    <Button
                                                        size="icon"
                                                        variant="edit"
                                                        onClick={() => handleEdit(item)}
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
                                ))
                            )}
                        </TableBody>
                    </Table>
                </div>

                {/* Footer Total */}
                {operationals.length > 0 && (
                    <div className="flex justify-end items-center gap-4 px-6 py-3 bg-yellow-100 border-t border-gray-300">
                        <p className="text-sm font-semibold text-gray-800">
                            Total Biaya Operasional
                        </p>
                        <p className="text-sm font-bold text-yellow-700">
                            {formatCurrency(totalOperational)}
                        </p>
                    </div>
                )}
            </div>
        </div>
    );
};
