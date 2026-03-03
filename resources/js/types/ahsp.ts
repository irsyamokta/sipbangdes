import { PageProps } from ".";

{/* Select Options */ }
interface SelectOption {
    value: string;
    label: string;
    filter?: any;
}

{/* AHSP */ }
interface Ahsp {
    id: string;
    work_code: string;
    work_name: string;
    unit: string;
    material_total: number;
    tool_total: number;
    wage_total: number;
    subtotal: number;
    ahsp_component_materials: AhspMaterial[];
    ahsp_component_wages: AhspWage[];
    ahsp_component_tools: AhspTool[];
    created_at: string;
    updated_at: string;
}

interface ModalAhspProps {
    isOpen: boolean;
    onClose: () => void;
    ahsp?: any;
    units: SelectOption[];
    materials?: SelectOption[];
}

interface AhspForm {
    work_name: string;
    unit: string;
}

interface AhspHeaderCardProps {
    ahsp: Ahsp;
    open?: boolean;
    deletingId?: string;
    subtotal?: number;
    toggle?: () => void;
    onEdit?: (ahsp: Ahsp) => void;
    onDelete?: (ahsp: Ahsp) => void;
}

interface AhspPageProps extends PageProps {
    ahsp: Ahsp[];
    unitOptions: SelectOption[];
    materialOptions: SelectOption[];
    wageOptions: SelectOption[];
    toolOptions: SelectOption[];
    filters: {
        search: string;
    }
}

{/* AHSP Materials */ }
interface AhspMaterial {
    id: string;
    ahsp_id: string;
    material_id: string;
    master_material: {
        id: string;
        name: string;
        unit: string;
        price: number;
    };
    coefficient: number;
}

interface ModalAhspMaterialProps {
    isOpen: boolean;
    onClose: () => void;
    ahspId: string;
    material?: AhspMaterial;
    materialOptions: SelectOption[];
}

interface AhspMaterialForm {
    ahsp_id: string;
    material_id: string;
    coefficient: number;
}

interface AhspMaterialTableProps {
    ahspId: string;
    materials: AhspMaterial[];
    materialOptions: SelectOption[];
}

{/* AHSP Wages */ }
interface AhspWage {
    id: string;
    ahsp_id: string;
    wage_id: string;
    master_wage: {
        id: string;
        position: string;
        unit: string;
        price: number;
    };
    coefficient: number;
}

interface ModalAhspWageProps {
    isOpen: boolean;
    onClose: () => void;
    ahspId: string;
    wage?: AhspWage;
    wageOptions: SelectOption[];
}

interface AhspWageForm {
    ahsp_id: string;
    wage_id: string;
    coefficient: number;
}

interface AhspWageTableProps {
    ahspId: string;
    wages: AhspWage[];
    wageOptions: SelectOption[];
}

{/* AHSP Tools */ }
interface AhspTool {
    id: string;
    ahsp_id: string;
    tool_id: string;
    master_tool: {
        id: string;
        name: string;
        unit: string;
        price: number;
    };
    coefficient: number;
}

interface ModalAhspToolProps {
    isOpen: boolean;
    onClose: () => void;
    ahspId: string;
    tool?: AhspTool;
    toolOptions: SelectOption[];
}

interface AhspToolForm {
    ahsp_id: string;
    tool_id: string;
    coefficient: number;
}

interface AhspToolTableProps {
    ahspId: string;
    tools: AhspTool[];
    toolOptions: SelectOption[];
}

export type {
    Ahsp,
    ModalAhspProps,
    AhspForm,
    AhspHeaderCardProps,
    AhspPageProps,
    AhspMaterial,
    ModalAhspMaterialProps,
    AhspMaterialForm,
    AhspMaterialTableProps,
    AhspWage,
    ModalAhspWageProps,
    AhspWageForm,
    AhspWageTableProps,
    AhspTool,
    ModalAhspToolProps,
    AhspToolForm,
    AhspToolTableProps
}
