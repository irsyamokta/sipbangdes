import { Head } from '@inertiajs/react';
import { usePage } from "@inertiajs/react";

import DashboardLayout from "@/Layouts/DashboardLayout";
import HeaderTitle from "@/Components/HeaderTitle";

export default function RAB() {
    return (
        <DashboardLayout>
            <Head title="RAB" />
            <div className="grid grid-cols-12 gap-4 md:gap-6">
                <div className="col-span-12 space-y-6 xl:col-span-12">
                    <HeaderTitle
                        title="Rencana Anggaran Biaya (RAB)"
                        subtitle="Hasil perhitungan otomatis dari TOS × AHSP × Master Harga"
                    />
                </div>
            </div>
        </DashboardLayout>
    );
}
