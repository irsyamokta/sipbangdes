import { usePage } from "@inertiajs/react";

import { useDelete } from "@/hooks/useDelete";
import { useSearch } from "@/hooks/useSearch";

import { MaterialPageProps, MaterialsTableProps } from "@/types/material";

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

export default function MaterialsTable({
    materials,
    last_page,
    links,
    onEdit
}: MaterialsTableProps) {
    const { props } = usePage<MaterialPageProps>();

    const { handleDelete, deletingId } = useDelete({
        routeName: "material.destroy",
        confirmTitle: "Hapus Material?",
        successMessage: "Material berhasil dihapus",
        errorMessage: "Gagal menghapus material",
    });

    const { filters, setFilter } = useSearch({
        routeName: "material.index",
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
                    placeholder="Cari material..."
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
                                    Nama Material
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
                            {materials.length === 0 ? (
                                <EmptyTable colspan={6} description="Tidak ada data material" />
                            ) : (
                                materials.map((material) => (
                                    <TableRow
                                        key={material.id}
                                        className="hover:bg-gray-50 transition"
                                    >
                                        {/* Code */}
                                        <TableCell className="px-6 py-4 text-sm font-medium text-gray-800 whitespace-nowrap">
                                            {material.code}
                                        </TableCell>

                                        {/* Nama */}
                                        <TableCell className="px-6 py-4 text-sm font-medium text-gray-800 whitespace-nowrap">
                                            {material.name}
                                        </TableCell>

                                        {/* Unit */}
                                        <TableCell className="px-6 py-4 text-sm text-gray-800 whitespace-nowrap">
                                            {material.unit}
                                        </TableCell>

                                        {/* price */}
                                        <TableCell className="px-6 py-4 text-sm font-medium text-gray-800 whitespace-nowrap">
                                            {formatCurrency(material.price)}
                                        </TableCell>

                                        {/* updated_at */}
                                        <TableCell className="px-6 py-4 text-sm font-medium text-gray-800 whitespace-nowrap">
                                            {formatDateTime(material.updated_at as string)}
                                        </TableCell>

                                        {/* Actions */}
                                        <TableCell className="px-6 py-4">
                                            <div className="flex justify-center gap-1">
                                                {/* Edit */}
                                                <Button
                                                    size="icon"
                                                    variant="edit"
                                                    onClick={() => onEdit(material)}
                                                >
                                                    <LuPencil size={18} />
                                                </Button>

                                                {/* Delete */}
                                                <Button
                                                    size="icon"
                                                    variant="danger"
                                                    onClick={() => handleDelete(material.id)}
                                                    disabled={deletingId === material.id}
                                                    className="disabled:opacity-50"
                                                >
                                                    {deletingId === material.id ? (
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
                            routeName="material.index"
                        />
                    </div>
                )}
            </div>
        </div>
    )
}
