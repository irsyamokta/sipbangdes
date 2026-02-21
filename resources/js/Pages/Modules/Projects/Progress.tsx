import { useState } from "react";
import { Head } from '@inertiajs/react';
import { usePage } from "@inertiajs/react";

import usePermission from "@/hooks/usePermission";

import { ProjectProgressPageProps } from "@/types/progress"

import DashboardLayout from "@/Layouts/DashboardLayout";
import PageBreadcrumb from "@/Components/ui/breadcrumb/Breadcrumb";
import Button from "@/Components/ui/button/Button";
import { ModalProgress } from "./Components/modal/ModalProgress";
import { CardProgress } from "./Components/card/CardProgress";
import { CardProgressHistory } from "./Components/card/CardProgressHistory";

import { LuPlus } from "react-icons/lu";

export default function Progress() {
    const { project, totalProgress } = usePage<ProjectProgressPageProps>().props;
    const { can } = usePermission();

    const [isModalOpen, setIsModalOpen] = useState(false);

    const canCreateProgress = can("progress.create");
    const isRunning = project.project_status === "berjalan";
    const isFinished = totalProgress === 100;

    return (
        <DashboardLayout>
            <Head title={project.project_name} />

            <ModalProgress
                isOpen={isModalOpen}
                onClose={() => {
                    setIsModalOpen(false);
                }}
                project={project}
                totalProgress={totalProgress}
            />

            <div className="grid grid-cols-12 gap-4 md:gap-6">
                {/* Header */}
                <div className="col-span-12 flex flex-col sm:flex-row justify-between gap-6">
                    <PageBreadcrumb
                        crumbs={[
                            { label: "Dashboard", href: route("dashboard") },
                            { label: "Proyek", href: route("project.index") },
                            { label: `${project.project_name}` },
                        ]}
                    />

                    {canCreateProgress && isRunning && !isFinished && (
                        <Button
                            startIcon={<LuPlus />}
                            className="lg:py-6"
                            onClick={() => {
                                setIsModalOpen(true);
                            }}
                        >
                            Tambah Progres
                        </Button>
                    )}
                </div>

                {/* Progress */}
                <div className="col-span-12 space-y-6">
                    <CardProgress
                        project={project}
                        totalProgress={totalProgress}
                    />
                </div>

                {/* Progress History */}
                <div className="col-span-12 space-y-6">
                    <CardProgressHistory
                        projectProgresses={project.project_progresses}
                    />
                </div>
            </div>
        </DashboardLayout>
    );
}
