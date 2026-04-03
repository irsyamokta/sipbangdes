import { useDelete } from "@/hooks/useDelete";

import { UsersTableProps } from "@/types/user";

import { EmptyTable } from "@/Components/empty/EmptyTable";
import Button from "@/Components/ui/button/Button";
import Badge from "@/Components/ui/badge/Badge";
import {
    Table,
    TableBody,
    TableCell,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";
import Pagination from "@/Components/ui/pagination/Pagination";

import { capitalizedFirst, capitalizeEachWord } from "@/utils/capitalize";
import { formatDateTime } from "@/utils/formatDate";

import { LuTrash2, LuPencil } from "react-icons/lu";

const roleBadgeColor = (role: string) => {
    switch (role) {
        case "approver":
            return "info";
        case "reviewer":
            return "warning";
        case "planner":
            return "success";
        case "admin":
            return "light";
        default:
            return "light";
    }
};

const UserTable = ({
    users,
    last_page,
    links,
    onEdit,
    filters
}: UsersTableProps) => {
    const { handleDelete, deletingId } = useDelete({
        routeName: "user.destroy",
        confirmTitle: "Hapus Pengguna?",
        successMessage: "Pengguna berhasil dihapus",
        errorMessage: "Gagal menghapus pengguna",
    });

    return (
        <div className="flex flex-col gap-4 mt-4">
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
                                    Nama Lengkap
                                </TableCell>

                                <TableCell
                                    isHeader
                                    className="px-6 py-3 text-sm font-semibold text-gray-900 text-start whitespace-nowrap"
                                >
                                    Email
                                </TableCell>

                                <TableCell
                                    isHeader
                                    className="px-6 py-3 text-sm font-semibold text-gray-900 text-start whitespace-nowrap"
                                >
                                    Role
                                </TableCell>

                                <TableCell
                                    isHeader
                                    className="px-6 py-3 text-sm font-semibold text-gray-900 text-start whitespace-nowrap"
                                >
                                    Status
                                </TableCell>

                                <TableCell
                                    isHeader
                                    className="px-6 py-3 text-sm font-semibold text-gray-900 text-start whitespace-nowrap"
                                >
                                    Dibuat
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
                            {users.length === 0 ? (
                                <EmptyTable colspan={6} description="Tidak ada data pengguna" />
                            ) : (
                                users.map((user) => (
                                    <TableRow
                                        key={user.id}
                                        className="hover:bg-gray-50 transition"
                                    >
                                        {/* Name */}
                                        <TableCell className="px-6 py-4 text-sm font-medium text-gray-800 whitespace-nowrap">
                                            {capitalizeEachWord(user.name)}
                                        </TableCell>

                                        {/* Email */}
                                        <TableCell className="px-6 py-4 text-sm text-gray-800 whitespace-nowrap">
                                            {user.email}
                                        </TableCell>

                                        {/* Role */}
                                        <TableCell className="px-6 py-4 whitespace-nowrap">
                                            <Badge
                                                color={roleBadgeColor(user.role) as any}
                                            >
                                                {capitalizedFirst(user.role)}
                                            </Badge>
                                        </TableCell>

                                        {/* Status */}
                                        <TableCell className="px-6 py-4 whitespace-nowrap">
                                            <Badge
                                                color={
                                                    user.is_active == "1"
                                                        ? "success"
                                                        : "error"
                                                }
                                            >
                                                {user.is_active ? "Aktif" : "Tidak Aktif"}
                                            </Badge>
                                        </TableCell>

                                        {/* Created */}
                                        <TableCell className="px-6 py-4 text-sm text-gray-800 whitespace-nowrap">
                                            {user.created_at ? formatDateTime(user.created_at) : "-"}
                                        </TableCell>

                                        {/* Actions */}
                                        <TableCell className="px-6 py-4">
                                            <div className="flex justify-center gap-1">

                                                {/* Edit */}
                                                <Button
                                                    size="icon"
                                                    variant="edit"
                                                    onClick={() => onEdit(user)}
                                                >
                                                    <LuPencil size={18} />
                                                </Button>

                                                {/* Delete */}
                                                <Button
                                                    size="icon"
                                                    variant="danger"
                                                    onClick={() => handleDelete(user.id)}
                                                    disabled={deletingId === user.id}
                                                    className="disabled:opacity-50"
                                                >
                                                    {deletingId === user.id ? (
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
                            routeName="user.index"
                        />
                    </div>
                )}
            </div>
        </div>
    );
}

export default UserTable;
