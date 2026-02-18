import { useState } from "react";
import { Head } from '@inertiajs/react';
import { usePage } from "@inertiajs/react";

import { Tool } from "@/types/tool";

import DashboardLayout from "@/Layouts/DashboardLayout";
import HeaderTitle from "@/Components/HeaderTitle";
import ToolsTable from './Components/table/ToolsTable';
import { ModalTool } from "./Components/modal/ModalTool";

import { LuPlus } from "react-icons/lu";

export default function Tools() {
    const { tools, units } = usePage().props as any;

    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedTool, setSelectedTool] = useState<Tool | null>(null);
    return (
        <DashboardLayout>
            <Head title="Alat" />

            <ModalTool
                isOpen={isModalOpen}
                onClose={() => {
                    setIsModalOpen(false);
                    setSelectedTool(null);
                }}
                tool={selectedTool}
                units={units}
            />

            <div className="grid grid-cols-12 gap-4 md:gap-6">
                <div className="col-span-12 space-y-6 xl:col-span-12">
                    <HeaderTitle
                        title="Master Alat"
                        subtitle="Kelola daftar peralatan konstruksi dan harga satuan"
                        actionLabel="Tambah Alat"
                        actionIcon={<LuPlus />}
                        onActionClick={() => setIsModalOpen(true)}
                    />
                </div>
                <div className="col-span-12 space-y-6 xl:col-span-12">
                    <ToolsTable
                        tools={tools.data}
                        last_page={tools.last_page}
                        links={tools.links}
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
