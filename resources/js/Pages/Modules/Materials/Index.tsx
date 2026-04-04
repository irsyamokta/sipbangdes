import { useState } from "react";
import { Head, usePage } from '@inertiajs/react';

import { useSearch } from "@/hooks/useSearch";

import { Material, MaterialPageProps } from "@/types/material";

import DashboardLayout from "@/Layouts/DashboardLayout";
import HeaderTitle from "@/Components/HeaderTitle";
import FilterBar from "@/Components/filter/FilterBar";
import MaterialTable from './Components/table/MaterialTable';
import MaterialModal from "./Components/modal/MaterialModal";

import { LuPlus } from "react-icons/lu";

export default function Materials() {
    const {
        props: {
            materials,
            unitOptions,
            filters: filter
        }
    } = usePage<MaterialPageProps>();

    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedMaterial, setSelectedMaterial] = useState<Material | null>(null);

    const { filters, setFilter } = useSearch({
        routeName: "material.index",
        initialFilters: {
            search: filter.search ?? "",
        },
    });

    return (
        <DashboardLayout>
            <Head title="Material" />

            {/* Modal */}
            <MaterialModal
                isOpen={isModalOpen}
                onClose={() => {
                    setIsModalOpen(false);
                    setSelectedMaterial(null);
                }}
                material={selectedMaterial}
                unitOptions={unitOptions}
            />

            {/* Content */}
            <div className="grid grid-cols-12 gap-4 md:gap-6">

                {/* Header */}
                <div className="col-span-12 space-y-6">
                    <HeaderTitle
                        title="Master Material"
                        subtitle="Kelola daftar material dan harga satuan"
                        actionLabel="Tambah Material"
                        actionIcon={<LuPlus />}
                        onActionClick={() => setIsModalOpen(true)}
                    />
                </div>

                <div className="col-span-12 space-y-6 mt-4">

                    {/* Filter Bar */}
                    <FilterBar
                        className="md:max-w-sm"
                        search={{
                            value: filters.search,
                            placeholder: "Cari material...",
                            onChange: (value) => setFilter("search", value),
                        }}
                    />

                    {/* Material Table */}
                    <MaterialTable
                        materials={materials.data}
                        last_page={materials.last_page}
                        links={materials.links}
                        filters={filters}
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
