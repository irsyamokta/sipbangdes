import { useDelete } from "@/hooks/useDelete";
import usePermission from "@/hooks/usePermission";

import {
    ProjectProgress,
    ProjectProgressCardHistoryProps,
} from "@/types/progress";

import Badge from "@/Components/ui/badge/Badge";
import Button from "@/Components/ui/button/Button";

import { formatDateTime } from "@/utils/formatDate";

import LightGallery from "lightgallery/react";
import lgZoom from "lightgallery/plugins/zoom";
import lgThumbnail from "lightgallery/plugins/thumbnail";

import "lightgallery/css/lightgallery.css";
import "lightgallery/css/lg-zoom.css";
import "lightgallery/css/lg-thumbnail.css";

import { capitalizedFirst } from "@/utils/capitalize";

import { IoCalendarOutline } from "react-icons/io5";
import { RxImage } from "react-icons/rx";
import { LuPencil, LuTrash2, LuLoaderCircle  } from "react-icons/lu";

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

const ProgressHistoryCard = ({
    projectProgresses,
    onEditProgress,
}: ProjectProgressCardHistoryProps & {
    onEditProgress?: (
        progress: ProjectProgress["project_progresses"][number],
    ) => void;
}) => {
    const { can } = usePermission();

    const { handleDelete, deletingId } = useDelete({
        routeName: "progress.delete-documents",
        confirmTitle: "Hapus Dokumentasi?",
        confirmText: "Foto yang dihapus tidak dapat dikembalikan!",
        successMessage: "Dokumentasi berhasil dihapus",
        errorMessage: "Dokumentasi gagal dihapus",
    });

    /* Empty state */
    if (!projectProgresses.length) {
        return (
            <div className="bg-white p-10 rounded-xl border border-gray-200 text-center text-gray-500 transition hover:shadow-md">
                Belum ada progres yang tercatat untuk proyek ini
            </div>
        );
    }

    return (
        <div className="space-y-4">
            {projectProgresses.map((progress) => (
                <div
                    key={progress.id}
                    className="space-y-4 rounded-2xl border border-gray-200 bg-white p-6 transition hover:shadow-md"
                >
                    {/* Header */}
                    <div className="flex items-center justify-between gap-2 border-b-2 border-gray-300 pb-4">
                        <div className="flex items-center gap-2">
                           
                           {/* Badge Percentage */}
                            <Badge
                                color={percentageBadgeColor(
                                    progress.percentage,
                                )}
                            >
                                {progress.percentage}%
                            </Badge>
                            
                            {/* Updated At */}
                            <div className="flex items-center gap-2 text-sm text-gray-500">
                                <span>
                                    <IoCalendarOutline />
                                </span>
                                <span>
                                   Diperbarui: {formatDateTime(progress.updated_at)}
                                </span>
                            </div>
                        </div>
                        
                        {/* Delete Button */}
                        {can("progress.edit") && (
                            <Button
                                size="icon"
                                variant="edit"
                                onClick={() => onEditProgress?.(progress)}
                            >
                                <LuPencil size={18} />
                            </Button>
                        )}
                    </div>

                    {/* Description */}
                    <div className="flex items-center gap-2 text-sm text-gray-900">
                        <p>{capitalizedFirst(progress.description)}</p>
                    </div>

                    {/* Images */}
                    <div className="flex flex-col gap-2 text-sm text-gray-900">
                        
                        {/* Total */}
                        <div className="flex items-center gap-2">
                            <span>
                                <RxImage />
                            </span>
                            <span>
                                Dokumentasi{" "}
                                {`(${progress.documents.length} file)`}
                            </span>
                        </div>

                        {/* Slider */}
                        <div className="mt-2">
                            <LightGallery
                                speed={500}
                                plugins={[lgZoom, lgThumbnail]}
                                elementClassNames="flex gap-2.5 overflow-x-auto pb-2 no-scrollbar"
                                selector="a"
                            >
                                {progress.documents.map((document) => (
                                    <div
                                        key={document.id}
                                        className="relative w-56 h-40 rounded-2xl overflow-hidden shrink-0 group"
                                    >
                                        <a
                                            href={document.image_url}
                                            className="block w-full h-full"
                                        >
                                            <img
                                                src={document.image_url}
                                                alt=""
                                                className="w-full h-full object-cover transition-all duration-300 group-hover:brightness-50"
                                            />
                                        </a>

                                        {deletingId === document.id && (
                                            <div className="absolute inset-0 flex items-center justify-center bg-black/60">
                                                <LuLoaderCircle  size={28} className="animate-spin text-white" />
                                            </div>
                                        )}

                                        {can("progress.delete") && (
                                            <div className="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                <button
                                                    type="button"
                                                    disabled={deletingId === document.id}
                                                    onClick={() => handleDelete(document.id)}
                                                    className="flex items-center justify-center w-8 h-8 rounded-full bg-red-500 text-white hover:bg-red-600 transition-colors duration-200 disabled:opacity-60 disabled:cursor-not-allowed"
                                                >
                                                    <LuTrash2 size={15} />
                                                </button>
                                            </div>
                                        )}
                                    </div>
                                ))}
                            </LightGallery>
                        </div>
                    </div>
                </div>
            ))}
        </div>
    );
};

export default ProgressHistoryCard;
