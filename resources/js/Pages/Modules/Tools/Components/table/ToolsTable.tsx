import { usePage } from "@inertiajs/react";

import { useDelete } from "@/hooks/useDelete";
import { useSearch } from "@/hooks/useSearch";

import { ToolPageProps, ToolsTableProps } from "@/types/tool";

import { EmptyTable } from "@/Components/empty/EmptyTable";
import Button from "@/Components/ui/button/Button";
import Input from "@/Components/form/input/InputField";
import {
    Table,
    TableBody,
    TableCell,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";
import Pagination from "@/Components/ui/pagination/Pagination";

import { formatDateTime } from "@/utils/formatDate";
import { formatCurrency } from "@/utils/formatCurrrency";

import { LuTrash2, LuPencil } from "react-icons/lu";
import { FiSearch } from "react-icons/fi";

export default function ToolsTable({
    tools,
    last_page,
    links,
    onEdit
}: ToolsTableProps) {
    const { props } = usePage<ToolPageProps>();

    const { handleDelete, deletingId } = useDelete({
        routeName: "tool.destroy",
        confirmTitle: "Hapus Alat?",
        successMessage: "Alat berhasil dihapus",
        errorMessage: "Gagal menghapus alat",
    });

    const { filters, setFilter } = useSearch({
        routeName: "tool.index",
        initialFilters: {
            search: props.filters.search ?? "",
        },
    });

    return (
        <div className="flex flex-col gap-4 mt-4">
            {/* Search */}
            <div className="flex items-center gap-2 w-full sm:w-1/3">
                <Input
                    type="text"
                    startIcon={<FiSearch />}
                    placeholder="Cari alat..."
                    value={filters.search}
                    onChange={(e) => setFilter("search", (e.target.value))}
                    className="w-full rounded-lg border-gray-300 text-sm"
                />
            </div>

            <div className="overflow-hidden rounded-xl border border-gray-200 bg-white">
                {/* Table */}
                <div className="max-w-full overflow-x-auto">
                    <Table>
                        {/* Header */}
                        <TableHeader className="bg-gray-100 border-b">
                            <TableRow>
                                <TableCell
                                    isHeader
                                    className="px-6 py-3 text-sm font-semibold text-gray-900 text-start whitespace-nowrap"
                                >
                                    Kode
                                </TableCell>

                                <TableCell
                                    isHeader
                                    className="px-6 py-3 text-sm font-semibold text-gray-900 text-start whitespace-nowrap"
                                >
                                    Nama Alat
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
                                    Harga Satuan
                                </TableCell>

                                <TableCell
                                    isHeader
                                    className="px-6 py-3 text-sm font-semibold text-gray-900 text-start whitespace-nowrap"
                                >
                                    Terakhir Diperbarui
                                </TableCell>

                                <TableCell
                                    isHeader
                                    className="px-6 py-3 text-sm font-semibold text-gray-900 text-center whitespace-nowrap"
                                >
                                    Aksi
                                </TableCell>
                            </TableRow>
                        </TableHeader>

                        {/* Body */}
                        <TableBody className="divide-y divide-gray-100">
                            {tools.length === 0 ? (
                                <EmptyTable colspan={6} description="Tidak ada data alat" />
                            ) : (
                                tools.map((tool) => (
                                    <TableRow
                                        key={tool.id}
                                        className="hover:bg-gray-50 transition"
                                    >
                                        {/* Code */}
                                        <TableCell className="px-6 py-4 text-sm font-medium text-gray-800 whitespace-nowrap">
                                            {tool.code}
                                        </TableCell>

                                        {/* Nama */}
                                        <TableCell className="px-6 py-4 text-sm font-medium text-gray-800 whitespace-nowrap">
                                            {tool.name}
                                        </TableCell>

                                        {/* Unit */}
                                        <TableCell className="px-6 py-4 text-sm text-gray-800 whitespace-nowrap">
                                            {tool.unit}
                                        </TableCell>

                                        {/* price */}
                                        <TableCell className="px-6 py-4 text-sm font-medium text-gray-800 whitespace-nowrap">
                                            {formatCurrency(tool.price)}
                                        </TableCell>

                                        {/* updated_at */}
                                        <TableCell className="px-6 py-4 text-sm font-medium text-gray-800 whitespace-nowrap">
                                            {formatDateTime(tool.updated_at as string)}
                                        </TableCell>

                                        {/* Actions */}
                                        <TableCell className="px-6 py-4">
                                            <div className="flex justify-center gap-1">
                                                {/* Edit */}
                                                <Button
                                                    size="icon"
                                                    variant="edit"
                                                    onClick={() => onEdit(tool)}
                                                >
                                                    <LuPencil size={18} />
                                                </Button>

                                                {/* Delete */}
                                                <Button
                                                    size="icon"
                                                    variant="danger"
                                                    onClick={() => handleDelete(tool.id)}
                                                    disabled={deletingId === tool.id}
                                                    className="disabled:opacity-50"
                                                >
                                                    {deletingId === tool.id ? (
                                                        <LuTrash2 size={18} className="animate-spin" />
                                                    ) : (
                                                        <LuTrash2 size={18} />
                                                    )}
                                                </Button>
                                            </div>
                                        </TableCell>
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
                            filters={filters}
                            routeName="tool.index"
                        />
                    </div>
                )}
            </div>
        </div>
    )
}
