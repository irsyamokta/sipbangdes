import { router } from "@inertiajs/react";

import usePermission from "@/hooks/usePermission";

import { ProjectCardProps } from "@/types/project";

import EmptyState from "@/Components/empty/EmptyState";
import Badge from "@/Components/ui/badge/Badge";
import Button from "@/Components/ui/button/Button";

import { LuMapPin, LuCalendar, LuUser, LuPencil, LuTrash2 } from "react-icons/lu";

import { capitalizeEachWord } from "@/utils/capitalize";

const badgeColor = (status: string) => {
    switch (status) {
        case "berjalan":
            return "warning";
        case "selesai":
            return "success";
        case "draft":
            return "light";
        default:
            return "light";
    }
};

export const CardProject = ({
    projects,
    deletingId,
    onEdit,
    onDelete,
    onTosClick,
    onRabClick,
}: ProjectCardProps) => {
    const { can } = usePermission();

    {/* Empty State */}
    if (!projects || projects.length === 0) {
        return (
            <div className="mt-4">
                <EmptyState
                    title="Tidak Ada Proyek"
                    description="Tidak menemukan proyek untuk ditampilkan."
                />
            </div>
        );
    }

    return (
        <div className="grid grid-cols-1 md:grid-cols-2 2xl:grid-cols-3 gap-4">
            {projects.map((project) => (
                <div
                    key={project.id}
                    className="rounded-2xl border border-gray-200 bg-white p-6 transition hover:shadow-md"
                >
                    {/* Header */}
                    <div
                        onClick={() => router.get(route("progress.show", project.id))}
                        className="flex items-start justify-between cursor-pointer"
                    >
                        <div>
                            <div className="flex items-center gap-3">
                                <h3 className="text-lg font-semibold text-gray-800 line-clamp-1">
                                    {capitalizeEachWord(project.project_name)}
                                </h3>

                                <Badge
                                    color={badgeColor(project.project_status)}
                                >
                                    {capitalizeEachWord(project.project_status)}
                                </Badge>
                            </div>

                            <div className="mt-3 space-y-1 text-sm text-gray-500">
                                <div className="flex items-center gap-2">
                                    <LuMapPin size={16} />
                                    <span>{project.location}</span>
                                </div>

                                <div className="flex items-center gap-2">
                                    <LuCalendar size={16} />
                                    <span>
                                        Tahun Anggaran {project.budget_year}
                                    </span>
                                </div>

                                <div className="flex items-center gap-2">
                                    <LuUser size={16} />
                                    <span>{capitalizeEachWord(project.chairman)}</span>
                                </div>
                            </div>
                        </div>

                        {/* Actions */}
                        <div className="flex -mt-1 justify-center">
                            {can("project.edit") && (
                                <Button
                                    size="icon"
                                    variant="edit"
                                    onClick={(e) => {
                                        e.stopPropagation();
                                        onEdit?.(project);
                                    }}
                                >
                                    <LuPencil size={18} />
                                </Button>
                            )}

                            {can("project.delete") && (
                                <Button
                                    size="icon"
                                    variant="danger"
                                    onClick={(e) => {
                                        e.stopPropagation();
                                        onDelete?.(project);
                                    }}
                                    disabled={deletingId === project.id}
                                >
                                    {deletingId === project.id ? (
                                        <LuTrash2 size={18} className="animate-spin" />
                                    ) : (
                                        <LuTrash2 size={18} />
                                    )}
                                </Button>
                            )}
                        </div>
                    </div>

                    {/* Progress */}
                    <div className="mt-6" >
                        <div className="mb-1 flex items-center justify-between text-sm text-gray-600">
                            <span>Progres</span>
                            <span>{project.progress_percentage}%</span>
                        </div>

                        <div className="h-2 w-full rounded-full bg-gray-200">
                            <div
                                className="h-2 rounded-full bg-primary transition-all duration-500"
                                style={{
                                    width: `${project.progress_percentage}%`,
                                }}
                            />
                        </div>
                    </div>

                    {/* Footer */}
                    <div className="mt-6 grid grid-cols-2 gap-4">
                        <Button
                            variant="outline"
                            onClick={() => onTosClick?.(project)}
                        >
                            TOS
                        </Button>

                        <Button
                            onClick={() => onRabClick?.(project)}
                        >
                            RAB
                        </Button>
                    </div>
                </div >
            ))}
        </div >
    );
};
