import { useState } from 'react';
import { Head, usePage } from '@inertiajs/react';

import usePermission from '@/hooks/usePermission';
import { useDelete } from "@/hooks/useDelete";

import { WorkerCategory, WokerCategoryPageProps } from "@/types/workerCategory";

import DashboardLayout from "@/Layouts/DashboardLayout";
import HeaderTitle from "@/Components/HeaderTitle";
import Accordion from '@/Components/ui/accordion/Accordion';
import EmptyState from "@/Components/empty/EmptyState";
import WorkerCategoryModal from './Components/modal/WorkerCategoryModal';
import WorkerCategoryHeaderCard from './Components/card/WorkerCategoryHeaderCard';
import WorkerItemTable from './Components/table/WorkerItemTable';

import { LuPlus } from "react-icons/lu";

export default function CategoryJob() {
    const {
        props: {
            workerCategories,
            unitOptions,
            ahspOptions
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
        errorMessage: "Gagal menghapus kategori pekerjaan",
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

                    {/* Worker Category List */}
                    <div className="flex flex-col gap-4">
                        {workerCategories.length == 0 ? (
                            <div className="mt-4">
                                <EmptyState
                                    title="Tidak Ada Kategori Pekerjaan"
                                    description="Tidak dapat menemukan kategori pekerjaan untuk ditampilkan."
                                />
                            </div>
                        ) : (
                            workerCategories.map((item) => (
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
