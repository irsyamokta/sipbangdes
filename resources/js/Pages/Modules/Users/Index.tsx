import { useState } from "react";
import { Head, usePage } from '@inertiajs/react';

import { useSearch } from '@/hooks/useSearch';

import { User, UserPageProps } from "@/types/user";

import DashboardLayout from "@/Layouts/DashboardLayout";
import HeaderTitle from "@/Components/HeaderTitle";
import FilterBar from "@/Components/filter/FilterBar";
import UserTable from './Components/table/UserTable';
import UserModal from "./Components/modal/UserModal";

import { LuPlus } from "react-icons/lu";

export default function Users() {
    const {
        props: {
            users,
            filters: filter
        },
    } = usePage<UserPageProps>();

    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedUser, setSelectedUser] = useState<User | null>(null);

    const { filters, setFilter } = useSearch({
        routeName: "user.index",
        initialFilters: {
            search: filter.search ?? "",
        }
    });

    return (
        <DashboardLayout>
            <Head title="Pengguna" />

            {/* Modal */}
            <UserModal
                isOpen={isModalOpen}
                onClose={() => {
                    setIsModalOpen(false);
                    setSelectedUser(null);
                }}
                user={selectedUser}
            />

            <div className="grid grid-cols-12 gap-4 md:gap-6">

                {/* Header */}
                <div className="col-span-12">
                    <HeaderTitle
                        title="Manajemen Pengguna"
                        subtitle="Kelola pengguna dan hak akses sistem"
                        actionLabel="Tambah Pengguna"
                        actionIcon={<LuPlus />}
                        onActionClick={() => setIsModalOpen(true)}
                    />
                </div>

                {/* Content */}
                <div className="col-span-12 space-y-6 mt-4">

                    {/* Filter Bar */}
                    <FilterBar
                        className="md:max-w-sm"
                        search={{
                            value: filters.search,
                            placeholder: "Cari berdasarkan nama atau email...",
                            onChange: (value) => setFilter("search", value),
                        }}
                    />

                    {/* User Table */}
                    <UserTable
                        users={users.data}
                        last_page={users.last_page}
                        links={users.links}
                        filters={filters}
                        onEdit={(user) => {
                            setSelectedUser(user);
                            setIsModalOpen(true);
                        }}
                    />
                </div>
            </div>
        </DashboardLayout>
    );
}
