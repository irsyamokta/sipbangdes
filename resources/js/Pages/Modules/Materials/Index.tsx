import { useState } from "react";
import { Head } from '@inertiajs/react';
import { usePage } from "@inertiajs/react";

import { Material } from "@/types/material";

import DashboardLayout from "@/Layouts/DashboardLayout";
import HeaderTitle from "@/Components/HeaderTitle";
import MaterialsTable from './Components/table/MaterialsTable';
import { ModalMaterial } from "./Components/modal/ModalMaterial";

import { LuPlus } from "react-icons/lu";

export default function Materials() {
    const { materials, units } = usePage().props as any;
    
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedMaterial, setSelectedMaterial] = useState<Material | null>(null);
    return (
        <DashboardLayout>
            <Head title="Material" />

            <ModalMaterial
                isOpen={isModalOpen}
                onClose={() => {
                    setIsModalOpen(false);
                    setSelectedMaterial(null);
                }}
                material={selectedMaterial}
                units={units}
            />

            <div className="grid grid-cols-12 gap-4 md:gap-6">
                <div className="col-span-12 space-y-6 xl:col-span-12">
                    <HeaderTitle
                        title="Master Material"
                        subtitle="Kelola daftar material dan harga satuan"
                        actionLabel="Tambah Material"
                        actionIcon={<LuPlus />}
                        onActionClick={() => setIsModalOpen(true)}
                    />
                </div>
                <div className="col-span-12 space-y-6 xl:col-span-12">
                    <MaterialsTable
                        materials={materials.data}
                        last_page={materials.last_page}
                        links={materials.links}
                        onEdit={(material) => {
                            setSelectedMaterial(material);
                            setIsModalOpen(true);
                        }}
                    />
                </div>
            </div>
        </DashboardLayout>
    );
}
