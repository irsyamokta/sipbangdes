import { useState } from "react";
import { Head } from '@inertiajs/react';
import { usePage } from "@inertiajs/react";

import { Unit } from "@/types/unit";

import DashboardLayout from "@/Layouts/DashboardLayout";
import HeaderTitle from "@/Components/HeaderTitle";
import UnitsTable from './Components/table/UnitsTable';
import { ModalUnit } from "./Components/modal/ModalUnit";

import { LuPlus } from "react-icons/lu";

export default function Units() {
    const { units } = usePage().props as any;

    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedUnit, setSelectedUnit] = useState<Unit | null>(null);

    return (
        <DashboardLayout>
            <Head title="Satuan" />

            <ModalUnit
                isOpen={isModalOpen}
                onClose={() => {
                    setIsModalOpen(false);
                    setSelectedUnit(null);
                }}
                unit={selectedUnit}
            />

            <div className="grid grid-cols-12 gap-4 md:gap-6">
                <div className="col-span-12 space-y-6 xl:col-span-12">
                    <HeaderTitle
                        title="Master Satuan"
                        subtitle="Kelola daftar satuan untuk material, upah, dan alat"
                        actionLabel="Tambah Satuan"
                        actionIcon={<LuPlus />}
                        onActionClick={() => setIsModalOpen(true)}
                    />
                </div>
                <div className="col-span-12 space-y-6 xl:col-span-12">
                    <UnitsTable
                        units={units.data}
                        last_page={units.last_page}
                        links={units.links}
                        onEdit={(unit) => {
                            setSelectedUnit(unit);
                            setIsModalOpen(true);
                        }}
                    />
                </div>
            </div>
        </DashboardLayout>
    );
}
