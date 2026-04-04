import { useState } from "react";
import { Head, usePage } from '@inertiajs/react';

import { useSearch } from "@/hooks/useSearch";

import { Wage, WagePageProps } from "@/types/wage";

import DashboardLayout from "@/Layouts/DashboardLayout";
import HeaderTitle from "@/Components/HeaderTitle";
import FilterBar from "@/Components/filter/FilterBar";
import WageTable from './Components/table/WageTable';
import WageModal from "./Components/modal/WageModal";

import { LuPlus } from "react-icons/lu";

export default function Wages() {
    const {
        props: {
            wages,
            unitOptions,
            filters: filter
        }
    } = usePage<WagePageProps>();

    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedWage, setSelectedWage] = useState<Wage | null>(null);

    const { filters, setFilter } = useSearch({
        routeName: "wage.index",
        initialFilters: {
            search: filter.search ?? "",
        },
    });

    return (
        <DashboardLayout>
            <Head title="Upah" />

            {/* Modal */}
            <WageModal
                isOpen={isModalOpen}
                onClose={() => {
                    setIsModalOpen(false);
                    setSelectedWage(null);
                }}
                wage={selectedWage}
                unitOptions={unitOptions}
            />

            {/* Content */}
            <div className="grid grid-cols-12 gap-4 md:gap-6">

                {/* Header */}
                <div className="col-span-12 space-y-6">
                    <HeaderTitle
                        title="Master Upah"
                        subtitle="Kelola daftar tenaga kerja dan harga satuan"
                        actionLabel="Tambah Upah"
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
                            placeholder: "Cari jabatan...",
                            onChange: (value) => setFilter("search", value),
                        }}
                    />

                    {/* Wage Table */}
                    <WageTable
                        wages={wages.data}
                        last_page={wages.last_page}
                        links={wages.links}
                        filters={filters}
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
