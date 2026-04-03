import { useState } from "react";
import { Head, usePage } from '@inertiajs/react';

import { useSearch } from "@/hooks/useSearch";

import { Unit, UnitPageProps } from "@/types/unit";

import DashboardLayout from "@/Layouts/DashboardLayout";
import HeaderTitle from "@/Components/HeaderTitle";
import FilterBar from "@/Components/filter/FilterBar";
import UnitTable from './Components/table/UnitTable';
import ModalUnit from "./Components/modal/UnitModal";

import { LuPlus } from "react-icons/lu";

export default function Units() {
    const {
        props: {
            units,
            filters: filter
        },
    } = usePage<UnitPageProps>();

    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedUnit, setSelectedUnit] = useState<Unit | null>(null);

    const { filters, setFilter } = useSearch({
        routeName: "unit.index",
        initialFilters: {
            search: filter.search ?? "",
        }
    });

    return (
        <DashboardLayout>
            <Head title="Satuan" />

            {/* Modal */}
            <ModalUnit
                isOpen={isModalOpen}
                onClose={() => {
                    setIsModalOpen(false);
                    setSelectedUnit(null);
                }}
                unit={selectedUnit}
            />

            {/* Content */}
            <div className="grid grid-cols-12 gap-4 md:gap-6">

                {/* Header */}
                <div className="col-span-12 space-y-6">
                    <HeaderTitle
                        title="Master Satuan"
                        subtitle="Kelola daftar satuan dan kategori satuan"
                        actionLabel="Tambah Satuan"
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
                            placeholder: "Cari satuan...",
                            onChange: (value) => setFilter("search", value),
                        }}
                    />

                    {/* Unit Table */}
                    <UnitTable
                        units={units.data}
                        last_page={units.last_page}
                        links={units.links}
                        filters={filters}
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
