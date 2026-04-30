import usePermission from "@/hooks/usePermission";

import { ProjectDetailTabsProps } from "@/types/progress";

import Tabs from "@/Components/ui/tabs/Tabs";
import Button from "@/Components/ui/button/Button";

import StatCard from "@/Components/card/StatCard";
import ProgressCard from "../card/ProgressCard";
import ProgressHistoryCard from "../card/ProgressHistoryCard";
import ExpenditureTable from "../table/ExpenditureTable";

import { formatCurrency } from "@/utils/formatCurrrency";

import { LuPlus, LuTrendingUp } from "react-icons/lu";
import { BiWallet } from "react-icons/bi";
import { PiMoneyWavyLight } from "react-icons/pi";
import { MdOutlineRealEstateAgent } from "react-icons/md";
import { IoWarningOutline } from "react-icons/io5";

const ProjectDetailTabs = ({
    project,
    expenditures,
    totalProgress,
    percentageBudget,
    totalBudget,
    totalRealization,
    remainingBudget,
    onOpenProgressModal,
    onOpenExpenditureModal,
    onEditProgress,
}: ProjectDetailTabsProps) => {
    const { can } = usePermission();

    const isRunning = project.project_status === "berjalan";
    const isFinished = totalProgress >= 100;
    const isBudgetFinished = percentageBudget >= 100;

    const tabs = [
        {
            key: "progress",
            label: "Progres Fisik",
            content: (
                <div className="space-y-6">
                    {can("progress.create") && isRunning && !isFinished && (
                        <div className="col-span-12 flex justify-end">
                            <Button
                                startIcon={<LuPlus />}
                                className="w-full md:w-auto lg:py-6"
                                onClick={onOpenProgressModal}
                            >
                                Tambah Progres
                            </Button>
                        </div>
                    )}

                    {/* Progress Project */}
                    <div className="col-span-12">
                        <ProgressCard
                            title={project.project_name}
                            description="Progres pelaksanaan proyek saat ini"
                            label="Progres"
                            value={totalProgress}
                            icon={<LuTrendingUp />}
                        />
                    </div>

                    {/* Progress History */}
                    <div className="col-span-12">
                        <ProgressHistoryCard
                            projectProgresses={project.project_progresses}
                            onEditProgress={onEditProgress}
                        />
                    </div>
                </div>
            ),
        },
        {
            key: "expenditure",
            label: "Realisasi Anggaran",
            content: (
                <div className="space-y-6">
                    {can("progress.create") && isRunning && !isBudgetFinished && (
                        <div className="col-span-12 flex justify-end">
                            <Button
                                startIcon={<LuPlus />}
                                className="w-full md:w-auto lg:py-6"
                                onClick={onOpenExpenditureModal}
                            >
                                Tambah Pengeluaran
                            </Button>
                        </div>
                    )}

                    {/* Budget Realization */}
                    <div className="col-span-12">
                        <ProgressCard
                            title="Penyerapan Anggaran"
                            description="Realisasi anggaran proyek saat ini"
                            label="Realisasi"
                            value={percentageBudget}
                            icon={<BiWallet />}
                        />
                    </div>

                    {/* Stat Cards */}
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {/* Total Budget */}
                        <StatCard
                            title="Total Anggaran"
                            value={formatCurrency(totalBudget)}
                            icon={<PiMoneyWavyLight size={24} />}
                            iconColor="text-green-700"
                            iconBgColor="bg-green-100"
                        />

                        {/* Total Realization */}
                        <StatCard
                            title="Total Realisasi"
                            value={formatCurrency(totalRealization)}
                            icon={<MdOutlineRealEstateAgent size={24} />}
                            iconColor="text-blue-700"
                            iconBgColor="bg-blue-100"
                        />

                        {/* Remaining Budget */}
                        <StatCard
                            title="Sisa Anggaran"
                            value={formatCurrency(remainingBudget)}
                            icon={<IoWarningOutline size={24} />}
                            iconColor="text-red-700"
                            iconBgColor="bg-red-100"
                        />
                    </div>

                    {/* Expenditure Table */}
                    <div className="col-span-12">
                        <ExpenditureTable
                            expenditures={expenditures}
                            remainingBudget={remainingBudget}
                        />
                    </div>
                </div>
            ),
        },
    ];

    return <Tabs tabs={tabs} storageKey="project-tabs" defaultTab="progress" />;
};

export default ProjectDetailTabs;
