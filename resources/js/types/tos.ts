import { PageProps } from ".";

interface SelectOption {
    value: string;
    label: string;
}

interface Project {
    id: string;
    project_name?: string;
}

interface WorkerCategory {
    id: string;
    name?: string;
}

interface Ahsp {
    id: string;
    work_name?: string;
    work_code?: string;
}

interface TakeOffSheet {
    id: string;
    project_id: string;
    ahsp_id: string;
    worker_category_id: string;

    work_name: string;

    project?: Project;
    worker_category?: WorkerCategory;
    ahsp?: Ahsp;

    unit: string;
    volume: number;

    locked_unit_price?: number;
    locked_at?: string;
    note?: string;

    created_at: string;
    updated_at: string;
}

interface TakeOffSheetForm {
    project_id: string;
    ahsp_id: string;
    worker_category_id: string;

    work_name: string;
    unit: string;
    volume: number;

    locked_unit_price?: number;
    note?: string;
}

interface ModalTakeOffSheetProps {
    isOpen: boolean;
    onClose: () => void;
    takeOffSheet: TakeOffSheet | null;

    projectOptions: SelectOption[];
    unitOptions: SelectOption[];
    workerCategoryOptions: SelectOption[];
    ahspOptions: SelectOption[];
}

interface TakeOffSheetTableProps {
    takeOffSheets: TakeOffSheet[];
    projectOptions: SelectOption[];
    workerCategoryOptions: SelectOption[];
    ahspOptions: SelectOption[];
    unitOptions: SelectOption[];

    last_page: number;
    links: any[];
}

interface Paginated<T> {
    data: T[];
    links: any[];
    current_page: number;
    last_page: number;
}

interface TakeOffSheetPageProps extends PageProps {
    takeOffSheets: Paginated<TakeOffSheet>;

    projectOptions: SelectOption[];
    workerCategoryOptions: SelectOption[];
    ahspOptions: SelectOption[];
    unitOptions: SelectOption[];

    filters: {
        search?: string;
        project_id?: string;
    };
}

export type {
    SelectOption,
    TakeOffSheet,
    TakeOffSheetForm,
    ModalTakeOffSheetProps,
    TakeOffSheetTableProps,
    TakeOffSheetPageProps,
};
