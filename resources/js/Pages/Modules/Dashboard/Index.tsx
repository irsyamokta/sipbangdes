import { Head, usePage } from '@inertiajs/react';

import { DashboardPageProps } from '@/types/dashboard';

import DashboardLayout from "@/Layouts/DashboardLayout";
import HeaderTitle from "@/Components/HeaderTitle";
import SubtotalCard from '@/Components/card/SubtotalCard';
import StatCard from '@/Components/card/StatCard';
import LatestProjectCard from './Components/card/LatestProjectCard';
import TopCategoryCard from './Components/card/TopCategoryCard';

import { formatCurrency } from '@/utils/formatCurrrency';

import { LuTrendingUp } from 'react-icons/lu';
import { VscProject } from "react-icons/vsc";
import { IoMdCheckmarkCircleOutline } from "react-icons/io";
import { LiaClipboardListSolid } from "react-icons/lia";
import { PiCalculatorLight } from "react-icons/pi";

export default function Dashboard() {
    const {
        props: {
            auth,
            data
        }
    } = usePage<DashboardPageProps>();

    return (
        <DashboardLayout>
            <Head title="Dashboard" />
            <div className="grid grid-cols-12 gap-4 md:gap-6">
                <div className="col-span-12 space-y-6">
                    <HeaderTitle
                        name={auth.user.name}
                        subtitle="Kelola sistem dan pantau seluruh aktivitas"
                    />
                </div>

                <div className="col-span-12 space-y-6 mt-4">
                    <SubtotalCard
                        title="Total Nilai RAB"
                        subtitle={`Dari ${data.rab_per_year[0].total_project} RAB yang disetujui pada tahun ini`}
                        icon={<LuTrendingUp size={24}/>}
                        value={formatCurrency(data.rab_per_year.reduce((acc, curr) => acc + curr.total_rab, 0))}
                    />

                    <div className="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                        <StatCard
                            variant="dashboard"
                            title="Total Proyek"
                            subtitle="Proyek terdaftar"
                            icon={<VscProject size={20} />}
                            iconBgColor="bg-blue-100"
                            iconColor="text-blue-600"
                            value={data.summary.total_project}
                        />

                        <StatCard
                            variant="dashboard"
                            title="Proyek Aktif"
                            subtitle="Proyek berjalan"
                            icon={<IoMdCheckmarkCircleOutline size={20} />}
                            iconBgColor="bg-green-100"
                            iconColor="text-green-600"
                            value={data.summary.active_project}
                        />

                        <StatCard
                            variant="dashboard"
                            title="Total AHSP"
                            subtitle="AHSP dibuat"
                            icon={<PiCalculatorLight size={20} />}
                            iconBgColor="bg-warning-100"
                            iconColor="text-warning-600"
                            value={data.summary.total_ahsp}
                        />

                        <StatCard
                            variant="dashboard"
                            title="Total TOS"
                            subtitle="Take Off Sheet dibuat"
                            icon={<LiaClipboardListSolid size={20} />}
                            iconBgColor="bg-red-100"
                            iconColor="text-red-600"
                            value={data.summary.total_tos}
                        />

                    </div>

                    <div className="grid grid-cols-1 gap-4 lg:grid-cols-2">
                        <LatestProjectCard projects={data.latest_projects} />
                        <TopCategoryCard categories={data.top_categories} />
                    </div>
                </div>
            </div>
        </DashboardLayout>
    );
}
