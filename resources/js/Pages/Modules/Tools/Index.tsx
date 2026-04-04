import { useState } from "react";
import { Head, usePage } from '@inertiajs/react';

import { useSearch } from "@/hooks/useSearch";

import { Tool, ToolPageProps } from "@/types/tool";

import DashboardLayout from "@/Layouts/DashboardLayout";
import HeaderTitle from "@/Components/HeaderTitle";
import FilterBar from "@/Components/filter/FilterBar";
import ToolTable from './Components/table/ToolTable';
import ToolModal from "./Components/modal/ToolModal";

import { LuPlus } from "react-icons/lu";

export default function Tools() {
    const {
        props: {
            tools,
            unitOptions,
            filters: filter
        },
    } = usePage<ToolPageProps>();

    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedTool, setSelectedTool] = useState<Tool | null>(null);

    const { filters, setFilter } = useSearch({
        routeName: "tool.index",
        initialFilters: {
            search: filter.search ?? "",
        },
    });

    return (
        <DashboardLayout>
            <Head title="Alat" />

            {/* Modal */}
            <ToolModal
                isOpen={isModalOpen}
                onClose={() => {
                    setIsModalOpen(false);
                    setSelectedTool(null);
                }}
                tool={selectedTool}
                unitOptions={unitOptions}
            />

            {/* Content */}
            <div className="grid grid-cols-12 gap-4 md:gap-6">

                {/* Header */}
                <div className="col-span-12 space-y-6">
                    <HeaderTitle
                        title="Master Alat"
                        subtitle="Kelola daftar peralatan konstruksi dan harga satuan"
                        actionLabel="Tambah Alat"
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
                            placeholder: "Cari alat...",
                            onChange: (value) => setFilter("search", value),
                        }}
                    />

                    {/* Tool Table */}
                    <ToolTable
                        tools={tools.data}
                        last_page={tools.last_page}
                        links={tools.links}
                        filters={filters}
                        onEdit={(tool) => {
                            setSelectedTool(tool);
                            setIsModalOpen(true);
                        }}
                    />
                </div>
            </div>
        </DashboardLayout>
    );
}
