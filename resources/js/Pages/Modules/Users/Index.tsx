import { useState } from "react";
import { Head } from '@inertiajs/react';
import { usePage } from "@inertiajs/react";

import { User } from "@/types/user";

import DashboardLayout from "@/Layouts/DashboardLayout";
import HeaderTitle from "@/Components/HeaderTitle";
import UsersTable from './Components/table/UsersTable';
import { ModalUser } from "./Components/modal/ModalUser";

import { LuPlus } from "react-icons/lu";

export default function Users() {
    const { users } = usePage().props as any;

    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedUser, setSelectedUser] = useState<User | null>(null);

    return (
        <DashboardLayout>
            <Head title="Pengguna" />

            <ModalUser
                isOpen={isModalOpen}
                onClose={() => {
                    setIsModalOpen(false);
                    setSelectedUser(null);
                }}
                user={selectedUser}
            />

            <div className="grid grid-cols-12 gap-4 md:gap-6">
                <div className="col-span-12 space-y-6 xl:col-span-12">
                    <HeaderTitle
                        title="Manajemen Pengguna"
                        subtitle="Kelola pengguna dan hak akses sistem"
                        actionLabel="Tambah Pengguna"
                        actionIcon={<LuPlus />}
                        onActionClick={() => setIsModalOpen(true)}
                    />
                </div>
                <div className="col-span-12 space-y-6 xl:col-span-12">
                    <UsersTable
                        users={users.data}
                        last_page={users.last_page}
                        links={users.links}
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
