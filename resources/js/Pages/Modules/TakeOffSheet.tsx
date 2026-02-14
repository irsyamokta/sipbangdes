import { Head } from '@inertiajs/react';
import { usePage } from "@inertiajs/react";

import DashboardLayout from "@/Layouts/DashboardLayout";
import HeaderTitle from "@/Components/HeaderTitle";

export default function TakeOffSheet() {
    return (
        <DashboardLayout>
            <Head title="Take off Sheet" />
            <div className="grid grid-cols-12 gap-4 md:gap-6">
                <div className="col-span-12 space-y-6 xl:col-span-12">
                    <HeaderTitle
                        title="Take Off Sheet"
                        subtitle="Pengukuran volume pekerjaan per proyek"
                    />
                </div>
            </div>
        </DashboardLayout>
    );
}
