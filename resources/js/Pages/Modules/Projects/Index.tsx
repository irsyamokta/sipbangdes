import { useState } from "react";
import { Head, usePage, router } from "@inertiajs/react";

import usePermission from "@/hooks/usePermission";
import { useSearch } from "@/hooks/useSearch";
import { useDelete } from "@/hooks/useDelete";
import { useBudgetYears } from "@/hooks/useBudgetYears";

import { Project, ProjectPageProps } from "@/types/project";

import DashboardLayout from "@/Layouts/DashboardLayout";
import HeaderTitle from "@/Components/HeaderTitle";
import FilterBar from "@/Components/filter/FilterBar";
import ProjectModal from "./Components/modal/ProjectModal";
import ProjectCard from "./Components/card/ProjectCard";

import { LuPlus } from "react-icons/lu";

export default function Projects() {
    const {
        props: { projects, unitOptions, filters: filter },
    } = usePage<ProjectPageProps>();

    const { can } = usePermission();

    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedProject, setSelectedProject] = useState<Project | null>(
        null,
    );

    const budgetYearOptions = useBudgetYears({
        startYear: 2025,
        includeAll: true,
    });

    const { handleDelete } = useDelete({
        routeName: "project.destroy",
        confirmTitle: "Hapus Proyek?",
        successMessage: "Proyek berhasil dihapus",
        errorMessage: "Gagal menghapus proyek",
    });

    const handleTosClick = (project: Project) => {
        router.get(route("tos.index"), {
            project_id: project.id,
        });
    };

    const handleRabClick = (project: Project) => {
        router.get(route("rab.index"), {
            project_id: project.id,
        });
    };

    const { filters, setFilter } = useSearch({
        routeName: "project.index",
        initialFilters: {
            search: filter.search ?? "",
            year: filter.year ?? "",
        },
    });

    return (
        <DashboardLayout>
            <Head title="Proyek" />

            {/* Modal */}
            <ProjectModal
                isOpen={isModalOpen}
                onClose={() => {
                    setIsModalOpen(false);
                    setSelectedProject(null);
                }}
                project={selectedProject || undefined}
                unitOptions={unitOptions}
            />

            <div className="grid grid-cols-12 gap-4 md:gap-6">
                {/* Header */}
                <div className="col-span-12">
                    <HeaderTitle
                        title="Daftar Proyek"
                        subtitle="Kelola proyek pembangunan desa"
                        actionLabel={
                            can("project.create") ? "Tambah Proyek" : undefined
                        }
                        actionIcon={
                            can("project.create") ? <LuPlus /> : undefined
                        }
                        onActionClick={() => setIsModalOpen(true)}
                    />
                </div>

                {/* Content */}
                <div className="col-span-12 space-y-6 mt-4">
                    {/* Filter Bar */}
                    <FilterBar
                        className="w-full md:max-w-2xl"
                        select={{
                            value: filters.year,
                            options: budgetYearOptions,
                            placeholder: "Pilih Tahun",
                            onChange: (value) => setFilter("year", value),
                        }}
                        search={{
                            value: filters.search,
                            placeholder: "Cari proyek...",
                            onChange: (value) => setFilter("search", value),
                        }}
                    />

                    {/* Cards */}
                    <ProjectCard
                        projects={projects}
                        onEdit={(project) => {
                            setSelectedProject(project);
                            setIsModalOpen(true);
                        }}
                        onDelete={(project) => handleDelete(project.id)}
                        onTosClick={handleTosClick}
                        onRabClick={handleRabClick}
                    />
                </div>
            </div>
        </DashboardLayout>
    );
}
