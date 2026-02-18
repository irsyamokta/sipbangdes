import { PageProps } from ".";

interface Material {
    id: string;
    code: string;
    name: string;
    unit: string;
    price: number;
    created_at?: string;
    updated_at?: string;
};

interface ModalMaterialProps {
    isOpen: boolean;
    onClose: () => void;
    material?: any;
    units: {
        values: string;
        labels: string;
        filter: any;
    }
};

interface MaterialForm {
    name: string;
    unit: string;
    price: number;
};

interface MaterialPageProps extends PageProps {
    materials: {
        data: Material[];
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

interface MaterialsTableProps {
    materials: Material[];
    last_page: number;
    links: any[];
    onEdit: (material: Material) => void;
};

export type {
    Material,
    ModalMaterialProps,
    MaterialForm,
    MaterialPageProps,
    MaterialsTableProps
};
