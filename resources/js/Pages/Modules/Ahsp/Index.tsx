import { useState } from "react";
import { Head } from '@inertiajs/react';
import { usePage } from "@inertiajs/react";

import usePermission from "@/hooks/usePermission";
import { useSearch } from "@/hooks/useSearch";
import { useDelete } from "@/hooks/useDelete";

import { Ahsp, AhspPageProps } from "@/types/ahsp";

import DashboardLayout from "@/Layouts/DashboardLayout";
import HeaderTitle from "@/Components/HeaderTitle";
import EmptyState from "@/Components/empty/EmptyState";
import FilterBar from "@/Components/filter/FilterBar";
import { ModalAhsp } from "./Components/modal/ModalAhsp";
import { AhspAccordion } from "./Components/accordion/AhspAccordion";
import { CardSummary } from "./Components/card/CardSummary";
import { MaterialTable } from "./Components/table/MaterialTable";
import { WageTable } from "./Components/table/WageTable";
import { ToolTable } from "./Components/table/ToolTable";

import { LuPlus } from "react-icons/lu";

export default function AHSP() {
    const {
        props: {
            ahsp,
            unitOptions,
            materialOptions,
            wageOptions,
            toolOptions,
            filters: filter
        }
    } = usePage<AhspPageProps>();
    const { can } = usePermission();

    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedAhsp, setSelectedAhsp] = useState<Ahsp | null>(null);
    const [openId, setOpenId] = useState<string | null>(null);

    const canCreate = can("ahsp.create");

    const toggle = (id: string) => {
        setOpenId((prev) => (prev === id ? null : id));
    };

    const { handleDelete } = useDelete({
        routeName: "ahsp.destroy",
        confirmTitle: "Hapus AHSP?",
        successMessage: "AHSP berhasil dihapus",
        errorMessage: "Gagal menghapus AHSP",
    });

    const { filters, setFilter } = useSearch({
        routeName: "ahsp.index",
        initialFilters: {
            search: filter.search ?? { search: "" },
        }
    });

    return (
        <DashboardLayout>
            <Head title="AHSP" />

            <ModalAhsp
                isOpen={isModalOpen}
                onClose={() => {
                    setIsModalOpen(false);
                    setSelectedAhsp(null);
                }}
                ahsp={selectedAhsp}
                units={unitOptions}
            />

            <div className="grid grid-cols-12 gap-4 md:gap-6">
                {/* Header */}
                <div className="col-span-12 space-y-6">
                    <HeaderTitle
                        title="Analisis Harga Satuan Pekerjaan (AHSP)"
                        subtitle="Daftar AHSP standar yang dapat digunakan lintas proyek"
                        actionLabel={canCreate ? "Tambah AHSP" : undefined}
                        actionIcon={canCreate ? <LuPlus /> : undefined}
                        onActionClick={() => setIsModalOpen(true)}
                    />
                </div>

                {/* Content */}
                <div className="col-span-12 space-y-6 mt-4">
                    <FilterBar
                        search={{
                            value: filters.search ?? "",
                            placeholder: "Cari satuan pekerjaan...",
                            onChange: (value) => setFilter("search", value),
                        }}
                    />

                    <div className="flex flex-col gap-4">
                        {ahsp.length === 0 ? (
                            <div className="mt-4">
                                <EmptyState
                                    title="Tidak Ada AHSP"
                                    description="Tidak dapat menemukan AHSP untuk ditampilkan."
                                />
                            </div>
                        ) : (
                            ahsp.map((item) => (
                                <AhspAccordion
                                    key={item.id}
                                    ahsp={item}
                                    open={openId === item.id}
                                    subtotal={item.subtotal}
                                    toggle={() => toggle(item.id)}
                                    onEdit={(item) => {
                                        setIsModalOpen(true);
                                        setSelectedAhsp(item);
                                    }}
                                    onDelete={(item) => handleDelete(item.id)}
                                >
                                    <CardSummary
                                        material_total={item.material_total}
                                        wage_total={item.wage_total}
                                        tool_total={item.tool_total}
                                    />
                                    <MaterialTable
                                        ahspId={item.id}
                                        materials={item.ahsp_component_materials}
                                        materialOptions={materialOptions}
                                    />
                                    <WageTable
                                        ahspId={item.id}
                                        wages={item.ahsp_component_wages}
                                        wageOptions={wageOptions}
                                    />
                                    <ToolTable
                                        ahspId={item.id}
                                        tools={item.ahsp_component_tools}
                                        toolOptions={toolOptions}
                                    />
                                </AhspAccordion>
                            ))
                        )}
                    </div>
                </div>
            </div>
        </DashboardLayout>
    );
}
