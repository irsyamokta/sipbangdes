import { useState } from "react";
import { Head, usePage } from "@inertiajs/react";

import usePermission from "@/hooks/usePermission";
import { useSearch } from "@/hooks/useSearch";
import { useDelete } from "@/hooks/useDelete";

import { Ahsp, AhspPageProps } from "@/types/ahsp";

import DashboardLayout from "@/Layouts/DashboardLayout";
import HeaderTitle from "@/Components/HeaderTitle";
import EmptyState from "@/Components/empty/EmptyState";
import FilterBar from "@/Components/filter/FilterBar";
import SummaryCard from "@/Components/card/SummaryCard";
import AhspModal from "./Components/modal/AhspModal";
import AhspAccordion from "./Components/accordion/AhspAccordion";
import MaterialTable from "./Components/table/MaterialTable";
import WageTable from "./Components/table/WageTable";
import ToolTable from "./Components/table/ToolTable";

import { LuPlus } from "react-icons/lu";

export default function AHSP() {
    const {
        props: {
            ahsp,
            unitOptions,
            materialOptions,
            wageOptions,
            toolOptions,
            filters: filter,
        },
    } = usePage<AhspPageProps>();

    const { can } = usePermission();

    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedAhsp, setSelectedAhsp] = useState<Ahsp | null>(null);
    const [openId, setOpenId] = useState<string | null>(null);

    const toggle = (id: string) => {
        setOpenId((prev) => (prev === id ? null : id));
    };

    const { handleDelete } = useDelete({
        routeName: "ahsp.destroy",
        confirmTitle: "Hapus AHSP?",
        successMessage: "AHSP berhasil dihapus",
        errorMessage: "Data masih digunakan, tidak dapat dihapus",
    });

    const { filters, setFilter } = useSearch({
        routeName: "ahsp.index",
        initialFilters: {
            search: filter.search ?? "",
            per_page: filter.per_page ?? "10",
        },
    });

    return (
        <DashboardLayout>
            <Head title="AHSP" />

            {/* Modal */}
            <AhspModal
                isOpen={isModalOpen}
                onClose={() => {
                    setIsModalOpen(false);
                    setSelectedAhsp(null);
                }}
                ahsp={selectedAhsp}
                unitOptions={unitOptions}
            />

            <div className="grid grid-cols-12 gap-4 md:gap-6">
                {/* Header */}
                <div className="col-span-12">
                    <HeaderTitle
                        title="Analisis Harga Satuan Pekerjaan (AHSP)"
                        subtitle="Daftar AHSP standar yang dapat digunakan lintas proyek"
                        actionLabel={
                            can("ahsp.create") ? "Tambah AHSP" : undefined
                        }
                        actionIcon={can("ahsp.create") ? <LuPlus /> : undefined}
                        onActionClick={() => setIsModalOpen(true)}
                    />
                </div>

                {/* Content */}
                <div className="col-span-12 space-y-6 mt-4">
                    {/* Filter Bar */}
                    <div className="flex justify-between gap-2">
                        <FilterBar
                            className="w-full md:max-w-sm"
                            search={{
                                value: filters.search ?? "",
                                placeholder: "Cari satuan pekerjaan...",
                                onChange: (value) => setFilter("search", value),
                            }}
                        />
                        
                        <FilterBar
                            className="w-32 md:max-w-24"
                            select={{
                                value: String(filters.per_page ?? "10"),
                                placeholder: "Lihat",
                                options: [
                                    { value: "10", label: "10" },
                                    { value: "25", label: "25" },
                                    { value: "50", label: "50" },
                                    { value: "all", label: "Semua" },
                                ],
                                onChange: (value) =>
                                    setFilter("per_page", value),
                                searchable: false,
                            }}
                        />
                    </div>

                    {/* AHSP List */}
                    <div className="flex flex-col gap-4">
                        {ahsp.data.length === 0 ? (
                            <div className="mt-4">
                                <EmptyState
                                    title="Tidak Ada AHSP"
                                    description="Tidak dapat menemukan AHSP untuk ditampilkan."
                                />
                            </div>
                        ) : (
                            ahsp.data.map((item) => (
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
                                    {/* Summary Card */}
                                    <SummaryCard
                                        material_total={item.material_total}
                                        wage_total={item.wage_total}
                                        tool_total={item.tool_total}
                                    />

                                    {/* Material Table */}
                                    <MaterialTable
                                        ahspId={item.id}
                                        materials={
                                            item.ahsp_component_materials
                                        }
                                        materialOptions={materialOptions}
                                    />

                                    {/* Wage Table */}
                                    <WageTable
                                        ahspId={item.id}
                                        wages={item.ahsp_component_wages}
                                        wageOptions={wageOptions}
                                    />

                                    {/* Tool Table */}
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
