import { PageProps } from ".";

interface Unit {
    id: string;
    code: string;
    name: string;
    category: string;
    created_at?: string;
    updated_at?: string;
};

interface ModalUnitProps {
    isOpen: boolean;
    onClose: () => void;
    unit?: any;
};

interface UnitForm {
    name: string;
    category: string;
};

interface UnitPageProps extends PageProps {
    units: {
        data: Unit[];
        links: any[];
        last_page: number;
    }
    filters: {
        search: string;
    }
};

interface UnitsTableProps {
    units: Unit[];
    last_page: number;
    links: any[];
    onEdit: (unit: Unit) => void;
};

export type {
    Unit,
    ModalUnitProps,
    UnitForm,
    UnitPageProps,
    UnitsTableProps
};
