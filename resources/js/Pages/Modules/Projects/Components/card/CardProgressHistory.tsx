import { ProjectProgressCardHistoryProps } from "@/types/progress";

import Badge from "@/Components/ui/badge/Badge";

import { formatDateTime } from "@/utils/formatDate";

import LightGallery from "lightgallery/react";
import lgZoom from "lightgallery/plugins/zoom";
import lgThumbnail from "lightgallery/plugins/thumbnail";

import "lightgallery/css/lightgallery.css";
import "lightgallery/css/lg-zoom.css";
import "lightgallery/css/lg-thumbnail.css";

import { IoCalendarOutline } from "react-icons/io5";
import { RxImage } from "react-icons/rx";

const percentageBadgeColor = (percentage: number) => {
    switch (percentage) {
        case 25:
            return "light";
        case 50:
            return "warning";
        case 75:
            return "info";
        case 100:
            return "success";
    }
};

export const CardProgressHistory = ({
    projectProgresses,
}: ProjectProgressCardHistoryProps) => {
    {/* Empty state */}
    if (!projectProgresses.length) {
        return (
            <div className="bg-white p-10 rounded-xl border border-gray-200 text-center text-gray-500 transition hover:shadow-md">
                Belum ada progres yang tercatat untuk proyek ini
            </div>
        );
    }

    return (
        <div className="space-y-4">
            {/* Progress History */}
            <h1 className="font-semibold">Riwayat Progres</h1>
            {projectProgresses.map((progress) => (
                <div key={progress.id} className="space-y-4 rounded-2xl border border-gray-200 bg-white p-6 transition hover:shadow-md">
                    {/* Header */}
                    <div className="flex items-center gap-2 border-b-2 border-gray-300 pb-4">
                        <Badge
                            color={percentageBadgeColor(progress.percentage)}
                        >
                            {progress.percentage}%
                        </Badge>
                        <div className="flex items-center gap-2 text-sm text-gray-500">
                            <span><IoCalendarOutline /></span>
                            <span>{formatDateTime(progress.created_at)}</span>
                        </div>
                    </div>

                    {/* Description */}
                    <div className="flex items-center gap-2 text-sm text-gray-900">
                        <p>{progress.description}</p>
                    </div>

                    {/* Documents */}
                    <div className="flex flex-col gap-2 text-sm text-gray-900">
                        <div className="flex items-center gap-2">
                            <span><RxImage /></span>
                            <span>Dokuemntasi {`(${progress.documents.length} file)`}</span>
                        </div>
                        <div className="mt-2">
                            <LightGallery
                                speed={500}
                                plugins={[lgZoom, lgThumbnail]}
                                elementClassNames="flex gap-2.5 overflow-x-auto pb-2 no-scrollbar"
                            >
                                {progress.documents.map((document) => (
                                    <a
                                        key={document.id}
                                        href={document.image_url}
                                        className="w-56 h-40 rounded-2xl overflow-hidden shrink-0"
                                    >
                                        <img
                                            src={document.image_url}
                                            alt=""
                                            className="w-full h-full object-cover"
                                        />
                                    </a>
                                ))}
                            </LightGallery>
                        </div>
                    </div>
                </div>
            ))}
        </div>
    )
}
