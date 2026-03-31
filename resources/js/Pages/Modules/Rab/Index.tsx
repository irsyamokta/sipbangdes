import { Head, usePage } from '@inertiajs/react';

import usePermission from '@/hooks/usePermission';
import { useSearch } from '@/hooks/useSearch';

import { RabPageProps } from '@/types/rab';

import DashboardLayout from "@/Layouts/DashboardLayout";
import HeaderTitle from "@/Components/HeaderTitle";
import StatCard from '@/Components/card/StatCard';
import FilterBar from '@/Components/filter/FilterBar';
import EmptyState from '@/Components/empty/EmptyState';
import ProjectInfoCard from './Components/card/ProjectInfoCard';
import RABTabs from './Components/tabs/RABTabs';
import SubtotalCard from './Components/card/SubtotalCard';
import RabHistoryCard from './Components/card/RabHistoryCard';
import RabActionButtons from './Components/button/RabActionButtons';

import { formatCurrency } from '@/utils/formatCurrrency';

import { LuWallet, LuTrendingUp } from "react-icons/lu";
import { PiPackage, PiPrinter } from "react-icons/pi";
import { LiaToolsSolid } from "react-icons/lia";
import { GrMoney } from "react-icons/gr";

export default function RAB() {
    const {
        props: {
            auth,
            rab,
            projectOptions,
            unitOptions,
            filters: filter
        }
    } = usePage<RabPageProps>();

    const { can } = usePermission();

    const role = auth.user.role;

    const { filters, setFilter } = useSearch({
        routeName: "rab.index",
        initialFilters: {
            project_id: filter?.project_id ?? ""
        }
    });

    const isSelected = !!filters.project_id;

    const handlePrint = () => {
        if (!filters.project_id) return;

        const url = `/rab/pdf?project_id=${filters.project_id}`;
        window.open(url, '_blank');
    };

    return (
        <DashboardLayout>
            <Head title="RAB" />

            <div className="grid grid-cols-12 gap-4 md:gap-6">
                {/* Header */}
                <div className="col-span-12">
                    <HeaderTitle
                        title="Rencana Anggaran Biaya (RAB)"
                        subtitle="Hasil perhitungan otomatis dari TOS × AHSP × Master Harga"
                        actionLabel={can("rab.download") && isSelected ? "Cetak RAB" : undefined}
                        actionIcon={can("rab.download") && isSelected ? <PiPrinter size={20} /> : undefined}
                        onActionClick={handlePrint}
                    />
                </div>

                {/* Content */}
                <div className="col-span-12 space-y-6 mt-4">

                    <div className="flex flex-col md:flex-row justify-between md:items-center gap-4">
                        {/* Filter */}
                        <FilterBar
                            className="md:max-w-sm"
                            select={{
                                value: filters.project_id,
                                options: projectOptions,
                                placeholder: "Pilih Proyek",
                                onChange: (value) => {
                                    setFilter("project_id", value);
                                },
                            }}
                        />

                        {/* Action Button */}
                        <RabActionButtons
                            role={role}
                            projectId={filters.project_id ?? ""}
                            status={rab?.project?.rab_status ?? ""}
                        />
                    </div>

                    {/* Empty */}
                    {!isSelected && (
                        <EmptyState
                            title="Belum ada proyek yang dipilih"
                            description="Silahkan pilih proyek terlebih dahulu untuk melihat RAB"
                        />
                    )}

                    {/* RAB */}
                    {isSelected && rab && (
                        <>
                            {/* Project Info */}
                            <ProjectInfoCard
                                name={rab.project?.project_name ?? '-'}
                                location={rab.project?.location ?? '-'}
                                leader={rab.project?.chairman ?? '-'}
                                year={rab.project?.budget_year ?? '-'}
                                status={rab.project?.rab_status ?? '-'}
                            />

                            {/* Stat Cards */}
                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                                <StatCard
                                    title="Total Material"
                                    value={formatCurrency(rab.summary?.material_total ?? 0)}
                                    icon={<PiPackage />}
                                    iconBgColor="bg-green-100"
                                    iconColor="text-green-600"
                                />

                                <StatCard
                                    title="Total Upah"
                                    value={formatCurrency(rab.summary?.wage_total ?? 0)}
                                    icon={<LuWallet />}
                                    iconBgColor="bg-warning-50"
                                    iconColor="text-warning-600"
                                />

                                <StatCard
                                    title="Total Alat"
                                    value={formatCurrency(rab.summary?.tool_total ?? 0)}
                                    icon={<LiaToolsSolid />}
                                    iconBgColor="bg-blue-100"
                                    iconColor="text-blue-600"
                                />

                                <StatCard
                                    title="Total Biaya Operasional"
                                    value={formatCurrency(rab.summary?.tool_total ?? 0)}
                                    icon={<GrMoney />}
                                    iconBgColor="bg-yellow-100"
                                    iconColor="text-yellow-600"
                                />
                            </div>

                            {/* Summary */}
                            <SubtotalCard
                                title="Total Rencana Anggaran Biaya"
                                subtitle={`${rab.detail?.length ?? 0} item pekerjaan`}
                                value={formatCurrency(rab.summary?.grand_total ?? 0)}
                                icon={<LuTrendingUp size={24} />}
                            />

                            {/* Tabs */}
                            <RABTabs
                                project_id={filters.project_id}
                                rab_status={rab.project?.rab_status ?? ''}
                                detail={rab.detail}
                                recapMaterial={rab.recap_material}
                                recapWage={rab.recap_wage}
                                recapTool={rab.recap_tool}
                                operational={rab.operational}
                                unitOptions={unitOptions}
                            />

                            {/* History */}
                            <RabHistoryCard history={rab.history ?? []} />
                        </>
                    )}
                </div>
            </div>
        </DashboardLayout>
    );
}
