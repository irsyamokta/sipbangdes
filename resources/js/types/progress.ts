import { PageProps } from ".";

interface ProjectProgress {
    id: string;
    project_name: string;
    project_status: string;
    project_progresses: {
        id: string;
        percentage: number;
        description: string;
        documents: {
            id: string;
            image_url: string;
        }[];
        created_at: string;
    }[];
    created_at: string;
}

interface ModalProjectProgressProps {
    isOpen: boolean;
    onClose: () => void;
    project?: any;
    totalProgress: number;
};

interface ProjectProgressForm {
    percentage: string;
    description: string;
    documents: File[];
};

interface ProjectProgressCardProps {
    project: ProjectProgress
    totalProgress?: number;
}

interface ProjectProgressCardHistoryProps {
    projectProgresses: ProjectProgress["project_progresses"];
}

interface ProjectProgressPageProps extends PageProps {
    project: ProjectProgress;
    totalProgress: number;
}

export type {
    ProjectProgress,
    ModalProjectProgressProps,
    ProjectProgressForm,
    ProjectProgressCardProps,
    ProjectProgressCardHistoryProps,
    ProjectProgressPageProps,
}
