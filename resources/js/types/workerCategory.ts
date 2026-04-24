import { PageProps } from ".";

{/* Select Options */ }
interface SelectOption {
    value: string;
    label: string;
    filter?: any;
    data?: any;
}

{/** Worker Category */ }
interface WorkerCategory {
    id: string;
    name: string;
    description: string;
    total_items: number;
    worker_items: WorkerItem[];
    created_at?: string;
    updated_at?: string;
}

interface ModalWorkerCategoryProps {
    isOpen: boolean;
    onClose: () => void;
    workerCategory?: any;
}

interface WorkerCategoryForm {
    name: string;
    description: string;
}

interface WorkerCategoryHeaderCardProps {
    workerCategory: WorkerCategory;
    open?: boolean;
    deletingId?: string;
    toggle?: () => void;
    onEdit?: (workerCategory: WorkerCategory) => void;
    onDelete?: (workerCategory: WorkerCategory) => void;
}

interface WokerCategoryPageProps extends PageProps {
    workerCategories: WorkerCategory[];
    unitOptions: SelectOption[];
    ahspOptions: SelectOption[];
}

{/** Worker Item */ }
interface WorkerItem {
    id: string;
    category_id: string;
    work_name: string;
    unit: string;
    ahsp_id: string;
    ahsp: {
        id: string;
        work_name: string;
        work_code: string;
    }
}

interface ModalWorkerItemProps {
    isOpen: boolean;
    onClose: () => void;
    categoryId: string;
    workerItem?: WorkerItem;
    unitOptions: SelectOption[];
    ahspOptions: SelectOption[];
}

interface WorkerItemForm {
    category_id: string;
    ahsp_id: string;
    work_name: string;
    unit: string;
}

interface WorkerItemTableProps {
    categoryId: string;
    workerItems: WorkerItem[];
    unitOptions: SelectOption[];
    ahspOptions: SelectOption[];
}

export type {
    WorkerCategory,
    SelectOption,
    ModalWorkerCategoryProps,
    WorkerCategoryForm,
    WorkerCategoryHeaderCardProps,
    WokerCategoryPageProps,
    WorkerItem,
    ModalWorkerItemProps,
    WorkerItemForm,
    WorkerItemTableProps
};
