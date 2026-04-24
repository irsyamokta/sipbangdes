import { useState } from "react";
import { Head, usePage } from "@inertiajs/react";

import usePermission from "@/hooks/usePermission";
import { useSearch } from "@/hooks/useSearch";

import { TakeOffSheet, TakeOffSheetPageProps } from "@/types/tos";

import DashboardLayout from "@/Layouts/DashboardLayout";
import HeaderTitle from "@/Components/HeaderTitle";
import FilterBar from "@/Components/filter/FilterBar";
import TakeOffSheetModal from "./Components/modal/TakeOffSheetModal";
import TakeOffSheetTable from "./Components/table/TakeOffSheetTable";

import { LuPlus } from "react-icons/lu";

export default function TOS() {
    const {
        props: {
            takeOffSheets,
            projectOptions,
            workerCategoryOptions,
            ahspOptions,
            unitOptions,
            filters: filter,
        },
    } = usePage<TakeOffSheetPageProps>();

    const { can } = usePermission();

    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedTakeOffSheet, setSelectedTakeOffSheet] =
        useState<TakeOffSheet | null>(null);

    const { filters, setFilter } = useSearch({
        routeName: "tos.index",
        initialFilters: {
            search: filter.search ?? "",
            project_id: filter.project_id ?? "",
        },
    });

    const projects = [{ value: "", label: "Semua Proyek" }, ...projectOptions];

    return (
        <DashboardLayout>
            <Head title="Take off Sheet" />

            {/* Modal */}
            <TakeOffSheetModal
                isOpen={isModalOpen}
                onClose={() => {
                    setIsModalOpen(false);
                    setSelectedTakeOffSheet(null);
                }}
                takeOffSheet={selectedTakeOffSheet}
                projectOptions={projectOptions}
                workerCategoryOptions={workerCategoryOptions}
                ahspOptions={ahspOptions}
                unitOptions={unitOptions}
            />

            <div className="grid grid-cols-12 gap-4 md:gap-6">
                {/* Header */}
                <div className="col-span-12">
                    <HeaderTitle
                        title="Take Off Sheet"
                        subtitle="Pengukuran volume pekerjaan per proyek"
                        actionLabel={
                            can("tos.create") ? "Tambah TOS" : undefined
                        }
                        actionIcon={can("tos.create") ? <LuPlus /> : undefined}
                        onActionClick={() => setIsModalOpen(true)}
                    />
                </div>

                {/* Content */}
                <div className="col-span-12 space-y-6 mt-4">
                    {/* Filter Bar */}
                    <FilterBar
                        className="w-full md:max-w-2xl"
                        select={{
                            value: filters.project_id,
                            options: projects,
                            placeholder: "Pilih Proyek",
                            onChange: (value) => setFilter("project_id", value),
                        }}
                        search={{
                            value: filters.search,
                            placeholder: "Cari pekerjaan...",
                            onChange: (value) => setFilter("search", value),
                        }}
                    />

                    {/* Take Off Sheet Table */}
                    <TakeOffSheetTable
                        takeOffSheets={takeOffSheets.data}
                        projectOptions={projectOptions}
                        workerCategoryOptions={workerCategoryOptions}
                        ahspOptions={ahspOptions}
                        unitOptions={unitOptions}
                        last_page={takeOffSheets.last_page}
                        links={takeOffSheets.links}
                    />
                </div>
            </div>
        </DashboardLayout>
    );
}
