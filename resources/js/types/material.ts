import { PageProps } from ".";

interface SelectOption {
    value: string;
    label: string;
};

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
    material?: Material | null;
    unitOptions: SelectOption[];
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
    unitOptions: SelectOption[];
    filters: {
        search: string;
    }
};

interface MaterialsTableProps {
    materials: Material[];
    last_page: number;
    links: any[];
    filters: {
        search: string;
    }
    onEdit: (material: Material) => void;
};

export type {
    Material,
    ModalMaterialProps,
    MaterialForm,
    MaterialPageProps,
    MaterialsTableProps
};
