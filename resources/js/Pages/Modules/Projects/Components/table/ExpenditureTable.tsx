import { useState } from "react";

import usePermission from "@/hooks/usePermission";
import { useDelete } from "@/hooks/useDelete";

import { ExpenditureTableProps, ProjectExpenditure } from "@/types/progress";

import Button from "@/Components/ui/button/Button";
import {
    Table,
    TableBody,
    TableCell,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";
import { EmptyTable } from "@/Components/empty/EmptyTable";
import ExpenditureModal from "../modal/ExpenditureModal";

import { formatCalendarDate } from "@/utils/formatDate";
import { formatCurrency } from "@/utils/formatCurrrency";
import { capitalizeEachWord } from "@/utils/capitalize";

import { LuPencil, LuTrash2 } from "react-icons/lu";

const ExpenditureTable = ({
    expenditures,
    remainingBudget
}: ExpenditureTableProps) => {

    const { can } = usePermission();

    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedExpenditure, setSelectedExpenditure] =
        useState<ProjectExpenditure | undefined>();

    const { handleDelete, deletingId } = useDelete({
        routeName: "expenditure.destroy",
        confirmTitle: "Hapus Pengeluaran?",
        successMessage: "Pengeluaran berhasil dihapus",
        errorMessage: "Gagal menghapus pengeluaran",
    });

    const actionColumn = can('progress.create');

    const colspan = actionColumn ? 6 : 5;

    return (
        <div>
            {/* Modal */}
            <ExpenditureModal
                isOpen={isModalOpen}
                onClose={() => {
                    setIsModalOpen(false);
                    setSelectedExpenditure(undefined);
                }}
                expenditure={selectedExpenditure}
                remainingBudget={remainingBudget}
            />

            <div className="overflow-hidden rounded-xl border border-gray-200 bg-white">
                <div className="max-w-full overflow-x-auto">
                    <Table>
                        {/* Header */}
                        <TableHeader className="bg-gray-200">
                            <TableRow>
                                <TableCell isHeader className="px-6 py-3 text-sm font-semibold text-start">
                                    No
                                </TableCell>

                                <TableCell isHeader className="px-6 py-3 text-sm font-semibold text-start">
                                    Uraian
                                </TableCell>

                                <TableCell isHeader className="px-6 py-3 text-sm font-semibold text-end">
                                    Tanggal
                                </TableCell>

                                <TableCell isHeader className="px-6 py-3 text-sm font-semibold text-end">
                                    Nominal
                                </TableCell>

                                <TableCell isHeader className="px-6 py-3 text-sm font-semibold text-end">
                                    Keterangan
                                </TableCell>

                                {actionColumn && (
                                    <TableCell isHeader className="px-6 py-3 text-sm font-semibold text-end">
                                        Aksi
                                    </TableCell>
                                )}

                            </TableRow>
                        </TableHeader>

                        {/* Body */}
                        <TableBody className="divide-y divide-gray-100 border-b">
                            {expenditures.length === 0 ? (
                                <EmptyTable
                                    colspan={colspan}
                                    description="Belum ada pengeluaran"
                                />

                            ) : (
                                expenditures.map((item, index) => (
                                    <TableRow
                                        key={item.id}
                                        className="hover:bg-gray-50 transition"
                                    >
                                        {/* No */}
                                        <TableCell className="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                            {index + 1}
                                        </TableCell>

                                        {/* Description */}
                                        <TableCell className="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                            {capitalizeEachWord(item.description)}
                                        </TableCell>

                                        {/* Date */}
                                        <TableCell className="px-6 py-4 text-sm text-end whitespace-nowrap">
                                            {formatCalendarDate(item.date)}
                                        </TableCell>

                                        {/* Nominal */}
                                        <TableCell className="px-6 py-4 text-sm text-end whitespace-nowrap">
                                            {formatCurrency(item.nominal)}
                                        </TableCell>

                                        {/* Information */}
                                        <TableCell className="px-6 py-4 text-sm text-end">
                                            {item.information ?? "-"}
                                        </TableCell>

                                        {/* Action */}
                                        {actionColumn && (
                                            <TableCell className="px-6 py-4 text-sm text-end whitespace-nowrap">
                                                <div className="flex justify-end gap-1">

                                                    {/* Edit */}
                                                    <Button
                                                        size="icon"
                                                        variant="edit"
                                                        onClick={() => {
                                                            setIsModalOpen(true);
                                                            setSelectedExpenditure(item);
                                                        }}
                                                    >
                                                        <LuPencil size={18} />
                                                    </Button>

                                                    {/* Delete */}
                                                    <Button
                                                        size="icon"
                                                        variant="danger"
                                                        onClick={() =>
                                                            handleDelete(item.id)
                                                        }
                                                        disabled={deletingId === item.id}
                                                        className="disabled:opacity-50"
                                                    >
                                                        {deletingId === item.id ? (
                                                            <LuTrash2
                                                                size={18}
                                                                className="animate-spin"
                                                            />
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
            </div>
        </div>
    );
}

export default ExpenditureTable;
