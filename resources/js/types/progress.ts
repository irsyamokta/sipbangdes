import { PageProps } from ".";

interface ProjectDetailTabsProps {
    project: ProjectProgress;
    expenditures: ProjectExpenditure[];
    totalProgress: number;
    percentageBudget: number;
    totalBudget: number;
    totalRealization: number;
    remainingBudget: number;
    onOpenProgressModal: () => void;
    onOpenExpenditureModal: () => void;
    onEditProgress?: (progress: ProjectProgress["project_progresses"][number]) => void;
}

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
        updated_at: string;
    }[];
    created_at: string;
}

interface ModalProjectProgressProps {
    isOpen: boolean;
    onClose: () => void;
    project?: any;
    totalProgress: number;
    progress?: ProjectProgress["project_progresses"][number] | null;
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
    expenditures: ProjectExpenditure[];
    totalProgress: number;
    percentageBudget: number;
    totalBudget: number;
    totalRealization: number;
    remainingBudget: number;
}

interface ProjectExpenditure {
    id: string;
    project_id: string;
    description: string;
    nominal: number;
    date: string;
    information: string;
    created_at: string;
    updated_at: string;
}

interface ProjectExpenditureForm {
    description: string;
    nominal: number;
    date: string;
    information: string;
}

interface ModalProjectExpenditureProps {
    isOpen: boolean;
    onClose: () => void;
    project?: any;
    expenditure?: ProjectExpenditure,
    remainingBudget?: number
}

interface ExpenditureTableProps {
    expenditures: ProjectExpenditure[];
    remainingBudget: number
}

export type {
    ProjectDetailTabsProps,
    ProjectProgress,
    ModalProjectProgressProps,
    ProjectProgressForm,
    ProjectProgressCardProps,
    ProjectProgressCardHistoryProps,
    ProjectProgressPageProps,
    ProjectExpenditure,
    ProjectExpenditureForm,
    ModalProjectExpenditureProps,
    ExpenditureTableProps
}
