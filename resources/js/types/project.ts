import { PageProps } from ".";

interface Project {
    id: string;
    project_name: string;
    location: string;
    chairman: string;
    project_status: string;
    progress_percentage: number;
    budget_year: string;
    created_at?: string;
    updated_at?: string;
};

interface ModalProjectProps {
    isOpen: boolean;
    onClose: () => void;
    project?: any;
};

interface ProjectForm {
    project_name: string;
    location: string;
    chairman: string;
    project_status: string;
    budget_year: string;
};

interface ProjectCardProps {
    projects: Project[];
    deletingId?: string;
    onEdit?: (project: Project) => void;
    onDelete?: (project: Project) => void;
    onTosClick?: (project: Project) => void;
    onRabClick?: (project: Project) => void;
};

interface ProjectPageProps extends PageProps {
    projects: Project[];
    filters: {
        search: string;
        year: string;
    }
};

export type {
    Project,
    ModalProjectProps,
    ProjectForm,
    ProjectCardProps,
    ProjectPageProps,
};
