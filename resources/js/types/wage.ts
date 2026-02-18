import { PageProps } from ".";

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
    wage?: any;
    units: {
        values: string;
        labels: string;
        filter: any;
    }
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
    units: {
        values: string;
        labels: string;
    }
    filters: {
        search: string;
    }
};

interface WagesTableProps {
    wages: Wage[];
    last_page: number;
    links: any[];
    onEdit: (wage: Wage) => void;
};

export type {
    Wage,
    ModalWageProps,
    WageForm,
    WagePageProps,
    WagesTableProps
};
