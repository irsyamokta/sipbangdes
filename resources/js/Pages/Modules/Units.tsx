import { Head } from '@inertiajs/react';
import { usePage } from "@inertiajs/react";

import DashboardLayout from "@/Layouts/DashboardLayout";
import HeaderTitle from "@/Components/HeaderTitle";

export default function Units() {
    return (
        <DashboardLayout>
            <Head title="Satuan" />
            <div className="grid grid-cols-12 gap-4 md:gap-6">
                <div className="col-span-12 space-y-6 xl:col-span-12">
                    <HeaderTitle
                        title="Master Satuan"
                        subtitle="Kelola daftar satuan untuk material, upah, dan alat"
                    />
                </div>
            </div>
        </DashboardLayout>
    );
}
