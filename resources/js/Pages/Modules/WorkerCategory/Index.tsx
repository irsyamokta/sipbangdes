import { useState } from 'react';
import { Head, usePage } from '@inertiajs/react';

import usePermission from '@/hooks/usePermission';
import { useSearch } from "@/hooks/useSearch";
import { useDelete } from "@/hooks/useDelete";

import { WorkerCategory, WokerCategoryPageProps } from "@/types/workerCategory";

import DashboardLayout from "@/Layouts/DashboardLayout";
import HeaderTitle from "@/Components/HeaderTitle";
import Accordion from '@/Components/ui/accordion/Accordion';
import EmptyState from "@/Components/empty/EmptyState";
import FilterBar from "@/Components/filter/FilterBar";
import WorkerCategoryModal from './Components/modal/WorkerCategoryModal';
import WorkerCategoryHeaderCard from './Components/card/WorkerCategoryHeaderCard';
import WorkerItemTable from './Components/table/WorkerItemTable';

import { LuPlus } from "react-icons/lu";

export default function CategoryJob() {
    const {
        props: {
            workerCategories,
            unitOptions,
            ahspOptions,
            filters: filter,
        }
    } = usePage<WokerCategoryPageProps>();
    
    const { can } = usePermission();

    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedWorkerCategory, setSelectedWorkerCategory] = useState<WorkerCategory | null>(null);
    const [openId, setOpenId] = useState<string | null>(null);

    const toggle = (id: string) => {
        setOpenId((prev) => (prev === id ? null : id));
    };

    const { handleDelete } = useDelete({
        routeName: "workercategory.destroy",
        confirmTitle: "Hapus Kategori Pekerjaan?",
        successMessage: "Kategori pekerjaan berhasil dihapus",
        errorMessage: "Data masih digunakan, tidak dapat dihapus",
    });

    const { filters, setFilter } = useSearch({
        routeName: "workercategory.index",
        initialFilters: {
            search: filter.search ?? "",
            per_page: filter.per_page ?? "10",
        },
    });

    return (
        <DashboardLayout>
            <Head title="Kategori Pekerjaan" />

            {/* Modal */}
            <WorkerCategoryModal
                isOpen={isModalOpen}
                onClose={() => {
                    setIsModalOpen(false);
                    setSelectedWorkerCategory(null);
                }}
                workerCategory={selectedWorkerCategory}
            />

            <div className="grid grid-cols-12 gap-4 md:gap-6">

                {/* Header */}
                <div className="col-span-12">
                    <HeaderTitle
                        title="Kategori Pekerjaan"
                        subtitle="Item pekerjaan yang dapat digunakan lintas proyek"
                        actionLabel={can('workercategory.create') ? "Tambah Kategori" : undefined}
                        actionIcon={can('workercategory.create') ? <LuPlus /> : undefined}
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
                                placeholder: "Cari kategori pekerjaan...",
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

                    {/* Worker Category List */}
                    <div className="flex flex-col gap-4">
                        {workerCategories.data.length == 0 ? (
                            <div className="mt-4">
                                <EmptyState
                                    title="Tidak Ada Kategori Pekerjaan"
                                    description="Tidak dapat menemukan kategori pekerjaan untuk ditampilkan."
                                />
                            </div>
                        ) : (
                            workerCategories.data.map((item) => (
                                <Accordion
                                    key={item.id}
                                    open={openId === item.id}
                                    renderHeader={() => (
                                        <WorkerCategoryHeaderCard
                                            workerCategory={item}
                                            open={openId === item.id}
                                            toggle={() => toggle(item.id)}
                                            onEdit={(item) => {
                                                setIsModalOpen(true);
                                                setSelectedWorkerCategory(item);
                                            }}
                                            onDelete={(item) => handleDelete(item.id)}
                                        />
                                    )}
                                >
                                    {/* Worker Item */}
                                    <WorkerItemTable
                                        categoryId={item.id}
                                        unitOptions={unitOptions}
                                        ahspOptions={ahspOptions}
                                        workerItems={item.worker_items ?? []}
                                    />
                                </Accordion>
                            ))
                        )}
                    </div>
                </div>
            </div>
        </DashboardLayout>
    );
}
