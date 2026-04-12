import { useState } from "react";
import { Head } from '@inertiajs/react';
import { usePage } from "@inertiajs/react";

import { ProjectProgressPageProps, ProjectExpenditure } from "@/types/progress"

import DashboardLayout from "@/Layouts/DashboardLayout";
import PageBreadcrumb from "@/Components/ui/breadcrumb/Breadcrumb";
import ProjectDetailTabs from "./Components/tabs/ProjectDetailTab";
import ProgressModal from "./Components/modal/ProgressModal";
import ExpenditureModal from "./Components/modal/ExpenditureModal";

export default function Detail() {
    const {
        props: {
            project,
            totalProgress,
            expenditures,
            totalBudget,
            totalRealization,
            remainingBudget,
            percentageBudget
        }
    } = usePage<ProjectProgressPageProps>();

    const [isProgressModalOpen, setIsProgressModalOpen] = useState(false);
    const [isExpenditureModalOpen, setIsExpenditureModalOpen] = useState(false);
    const [selectedExpenditure, setSelectedExpenditure] = useState<ProjectExpenditure | null>(null);

    return (
        <DashboardLayout>
            <Head title={project.project_name} />

            {/* Progress Modal */}
            <ProgressModal
                isOpen={isProgressModalOpen}
                onClose={() => {
                    setIsProgressModalOpen(false);
                }}
                project={project}
                totalProgress={totalProgress}
            />

            {/* Expenditure Modal */}
            <ExpenditureModal
                isOpen={isExpenditureModalOpen}
                onClose={() => {
                    setIsExpenditureModalOpen(false);
                    setSelectedExpenditure(null);
                }}
                project={project}
                expenditure={selectedExpenditure || undefined}
                remainingBudget={remainingBudget}
            />

            <div className="grid grid-cols-12 gap-4 md:gap-6">

                {/* Header */}
                <div className="col-span-12">
                    <PageBreadcrumb
                        crumbs={[
                            { label: "Dashboard", href: route("dashboard") },
                            { label: "Proyek", href: route("project.index") },
                            { label: `${project.project_name}` },
                        ]}
                    />
                </div>

                {/* Tabs */}
                <div className="col-span-12">
                    <ProjectDetailTabs
                        project={project}
                        expenditures={expenditures}
                        totalProgress={totalProgress}
                        percentageBudget={percentageBudget}
                        totalBudget={totalBudget}
                        totalRealization={totalRealization}
                        remainingBudget={remainingBudget}
                        onOpenProgressModal={() =>
                            setIsProgressModalOpen(true)
                        }
                        onOpenExpenditureModal={() =>
                            setIsExpenditureModalOpen(true)
                        }
                    />
                </div>
            </div>
        </DashboardLayout>
    );
}
