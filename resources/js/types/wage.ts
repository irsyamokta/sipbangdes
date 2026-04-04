import { PageProps } from ".";

interface SelectOption {
    value: string;
    label: string;
};

interface Wage {
    id: string;
    code: string;
    position: string;
    unit: string;
    price: number;
    created_at?: string;
    updated_at?: string;
};

interface ModalWageProps {
    isOpen: boolean;
    onClose: () => void;
    wage?: Wage | null;
    unitOptions: SelectOption[];
};

interface WageForm {
    position: string;
    unit: string;
    price: number;
};

interface WagePageProps extends PageProps {
    wages: {
        data: Wage[];
        links: any[];
        last_page: number;
    }
    unitOptions: SelectOption[];
    filters: {
        search: string;
    }
};

interface WagesTableProps {
    wages: Wage[];
    last_page: number;
    links: any[];
    filters: {
        search: string;
    }
    onEdit: (wage: Wage) => void;
};

export type {
    Wage,
    ModalWageProps,
    WageForm,
    WagePageProps,
    WagesTableProps
};
