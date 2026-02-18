import { useState } from "react";
import { Head } from '@inertiajs/react';
import { usePage } from "@inertiajs/react";

import { Wage } from "@/types/wage";

import DashboardLayout from "@/Layouts/DashboardLayout";
import HeaderTitle from "@/Components/HeaderTitle";
import WagesTable from './Components/table/WagesTable';
import { ModalWage } from "./Components/modal/ModalWage";

import { LuPlus } from "react-icons/lu";

export default function Wages() {
    const { wages, units } = usePage().props as any;

    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedWage, setSelectedWage] = useState<Wage | null>(null);
    return (
        <DashboardLayout>
            <Head title="Upah" />

            <ModalWage
                isOpen={isModalOpen}
                onClose={() => {
                    setIsModalOpen(false);
                    setSelectedWage(null);
                }}
                wage={selectedWage}
                units={units}
            />

            <div className="grid grid-cols-12 gap-4 md:gap-6">
                <div className="col-span-12 space-y-6 xl:col-span-12">
                    <HeaderTitle
                        title="Master Upah"
                        subtitle="Kelola daftar tenaga kerja dan harga satuan"
                        actionLabel="Tambah Upah"
                        actionIcon={<LuPlus />}
                        onActionClick={() => setIsModalOpen(true)}
                    />
                </div>
                <div className="col-span-12 space-y-6 xl:col-span-12">
                    <WagesTable
                        wages={wages.data}
                        last_page={wages.last_page}
                        links={wages.links}
                        onEdit={(wage) => {
                            setSelectedWage(wage);
                            setIsModalOpen(true);
                        }}
                    />
                </div>
            </div>
        </DashboardLayout>
    );
}
