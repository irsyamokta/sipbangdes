import { Head } from '@inertiajs/react';
import { usePage } from "@inertiajs/react";

import DashboardLayout from "@/Layouts/DashboardLayout";
import HeaderTitle from "@/Components/HeaderTitle";

export default function Dashboard() {
    const { auth }: any = usePage().props;

    return (
        <DashboardLayout>
            <Head title="Dashboard" />
            <div className="grid grid-cols-12 gap-4 md:gap-6">
                <div className="col-span-12 space-y-6 xl:col-span-12">
                    <HeaderTitle name={auth.user.name} />
                </div>
            </div>
        </DashboardLayout>
    );
}
