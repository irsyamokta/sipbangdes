import { useState } from "react";
import { Head } from '@inertiajs/react';
import { usePage } from "@inertiajs/react";

import usePermission from "@/hooks/usePermission";
import { useSearch } from "@/hooks/useSearch";
import { useDelete } from "@/hooks/useDelete";
import { useBudgetYears } from "@/hooks/useBudgetYears";

import { Project, ProjectPageProps } from "@/types/project";

import DashboardLayout from "@/Layouts/DashboardLayout";
import HeaderTitle from "@/Components/HeaderTitle";
import FilterBar from "@/Components/filter/FilterBar";
import { ModalProject } from "./Components/modal/ModalProject";
import { CardProject } from "./Components/card/CardProject"

import { LuPlus } from "react-icons/lu";

export default function Projects() {
    const { props: { projects, filters: filter } } = usePage<ProjectPageProps>();
    const { can } = usePermission();

    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedProject, setSelectedProject] = useState<Project | null>(null);

    const canCreateProject = can("project.create");
    const budgetYearOptions = useBudgetYears({ startYear: 2026 });

    const { handleDelete } = useDelete({
        routeName: "project.destroy",
        confirmTitle: "Hapus Proyek?",
        successMessage: "Proyek berhasil dihapus",
        errorMessage: "Gagal menghapus proyek",
    });

    const { filters, setFilter } = useSearch({
        routeName: "project.index",
        initialFilters: {
            search: filter.search ?? "",
            year: filter.year ?? ""
        }
    });

    return (
        <DashboardLayout>
            <Head title="Proyek" />

            <ModalProject
                isOpen={isModalOpen}
                onClose={() => {
                    setIsModalOpen(false);
                    setSelectedProject(null);
                }}
                project={selectedProject}
            />

            <div className="grid grid-cols-12 gap-4 md:gap-6">
                {/* Header */}
                <div className="col-span-12 space-y-6">
                    <HeaderTitle
                        title="Daftar Proyek"
                        subtitle="Kelola proyek pembangunan desa"
                        actionLabel={canCreateProject ? "Tambah Proyek" : undefined}
                        actionIcon={canCreateProject ? <LuPlus /> : undefined}
                        onActionClick={() => setIsModalOpen(true)}
                    />
                </div>

                {/* Content */}
                <div className="col-span-12 space-y-6 mt-4">
                    <FilterBar
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
                    <CardProject
                        projects={projects}
                        onEdit={(project) => {
                            setSelectedProject(project);
                            setIsModalOpen(true);
                        }}
                        onDelete={(project) => handleDelete(project.id)}

                        onTosClick={(project) => {
                            console.log("tos", project.id);
                        }}

                        onRabClick={(project) => {
                            console.log("rab", project.id);
                        }}
                    />
                </div>
            </div>
        </DashboardLayout>
    );
}
