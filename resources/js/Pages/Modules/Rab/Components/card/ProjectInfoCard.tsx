import React from "react";
import rabStatusMap from "@/config/RabStatusMap";
import Badge from "@/Components/ui/badge/Badge";

interface ProjectInfoCardProps {
    name: string;
    location: string;
    leader: string;
    year: string | number;
    status: string;
};

const ProjectInfoCard: React.FC<ProjectInfoCardProps> = ({
    name,
    location,
    leader,
    year,
    status,
}) => {
    const statusConfig = rabStatusMap[status] || {
        label: status,
        color: "info",
    };

    return (
        <div className="w-full rounded-2xl border border-gray-300 bg-white py-8 px-4 hover:shadow-md">
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-center">

                {/* Nama Proyek */}
                <div className="flex flex-col gap-1">
                    <p className="text-gray-600 text-sm">Nama Proyek</p>
                    <p className="font-semibold text-gray-800">{name}</p>
                </div>

                {/* Lokasi */}
                <div className="flex flex-col gap-1">
                    <p className="text-gray-600 text-sm">Lokasi</p>
                    <p className="font-semibold text-gray-800">{location}</p>
                </div>

                {/* Ketua TPK */}
                <div className="flex flex-col gap-1">
                    <p className="text-gray-600 text-sm">Ketua TPK</p>
                    <p className="font-semibold text-gray-800">{leader}</p>
                </div>

                {/* Tahun */}
                <div className="flex flex-col gap-1">
                    <p className="text-gray-600 text-sm">Tahun Anggaran</p>
                    <p className="font-semibold text-gray-800">{year}</p>
                </div>

                {/* Status */}
                <div className="flex flex-col gap-1 max-w-30">
                    <p className="text-gray-600 text-sm">Status</p>
                    <Badge
                        variant="light"
                        color={statusConfig.color}
                        size="md"
                    >
                        {statusConfig.label}
                    </Badge>
                </div>
            </div>
        </div>
    );
};

export default ProjectInfoCard;
