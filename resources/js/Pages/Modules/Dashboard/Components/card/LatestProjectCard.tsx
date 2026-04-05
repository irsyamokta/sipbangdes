import { Link } from "@inertiajs/react";

import { LatestProject } from "@/types/dashboard";

import Badge from "@/Components/ui/badge/Badge";
import Button from "@/Components/ui/button/Button";
import { EmptyTable } from "@/Components/empty/EmptyTable";

import { formatCurrency } from "@/utils/formatCurrrency";

import { GoChevronRight } from "react-icons/go";

interface Props {
    projects: LatestProject[];
}

const getStatusBadge = (status: string) => {
    switch (status.toLowerCase()) {
        case "draft":
            return { color: "light", label: "Draft" };
        case "berjalan":
            return { color: "warning", label: "Berjalan" };
        case "selesai":
            return { color: "success", label: "Selesai" };
        default:
            return { color: "dark", label: status };
    }
};

const LatestProjectCard = ({ projects }: Props) => {
    const isEmpty = !projects || projects.length === 0;

    return (
        <div className="bg-white border border-gray-300 rounded-2xl p-4">
            {/* Header */}
            <div className="flex justify-between items-center mb-4">
                <h2 className="font-semibold text-gray-800">
                    Proyek Terbaru
                </h2>

                <Link href={route("project.index")}>
                    <Button
                        size="none"
                        variant="link"
                        endIcon={<GoChevronRight size={18} />}
                        className="text-sm"
                    >
                        Lihat Semua
                    </Button>
                </Link>
            </div>

            {/* Content */}
            {isEmpty ? (
                <div className="flex justify-center mt-16 py-6">
                    <EmptyTable
                        colspan={1}
                        description="Belum ada proyek"
                    />
                </div>
            ) : (
                <div className="space-y-4">
                    {projects.map((project) => {
                        const status = getStatusBadge(project.status);

                        return (
                            <div
                                key={project.id}
                                className="flex flex-col gap-2"
                            >
                                <div className="flex justify-between">
                                    {/* Left */}
                                    <div>
                                        <h3 className="font-medium text-gray-900">
                                            {project.project_name}
                                        </h3>

                                        <p className="text-sm text-gray-500 mt-1">
                                            {project.location} | Tahun{" "}
                                            {project.budget_year}
                                        </p>
                                    </div>

                                    {/* Right */}
                                    <div className="text-right flex flex-col items-end gap-2">
                                        <Badge
                                            size="sm"
                                            color={status.color as any}
                                        >
                                            {status.label}
                                        </Badge>
                                    </div>
                                </div>

                                {/* Bottom */}
                                <div className="flex justify-between items-center">
                                    <p className="text-sm text-gray-500 mt-1">
                                        {project.total_items} item pekerjaan
                                    </p>

                                    <span className="font-semibold text-gray-900">
                                        {formatCurrency(project.subtotal)}
                                    </span>
                                </div>
                            </div>
                        );
                    })}
                </div>
            )}
        </div>
    );
};

export default LatestProjectCard;
