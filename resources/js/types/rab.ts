import { PageProps } from ".";

interface SelectOption {
    value: string;
    label: string;
}

interface Project {
    id: string;
    project_name: string;
    location: string;
    chairman: string;
    budget_year: number;
    project_status: string;
    rab_status: string;
}

interface RabSummary {
    material_total: number;
    wage_total: number;
    tool_total: number;
    operational_total: number;
    grand_total: number;
}

interface RabComponentItem {
    name: string;
    unit: string;

    coefficient: number;
    qty: number;

    price: number;
    total: number;
}

interface RabDetail {
    id: string;

    ahsp_id: string;

    work_code: string;
    work_name: string;
    category: string;

    unit: string;
    volume: number;

    material_total: number;
    wage_total: number;
    tool_total: number;
    subtotal: number;

    materials: RabComponentItem[];
    wages: RabComponentItem[];
    tools: RabComponentItem[];
}

interface RabOperational {
    id: string;
    project_id: string;
    name: string;
    unit: string;

    volume: number;
    unit_price: number;
    total: number;
}

interface RabHistory {
    id: string;
    user: string;
    role: string;
    action: string;
    comment: string;
    date: string;
}

interface RabDetailHeaderProps {
    id: string;

    work_code: string;
    work_name: string;
    category: string;

    unit: string;
    volume: number;

    subtotal: number;

    open: boolean;
    toggle: () => void;
}

interface RabMaterialRecap {
    material_id: string;
    name: string;
    unit: string;

    quantity: number;
    price: number;
    total: number;
}

interface RabWageRecap {
    wage_id: string;
    name: string;
    unit: string;

    quantity: number;
    price: number;
    total: number;
}

interface RabToolRecap {
    tool_id: string;
    name: string;
    unit: string;

    quantity: number;
    price: number;
    total: number;
}

interface Rab {
    project: Project;

    summary: RabSummary;

    detail: RabDetail[];

    recap_material: RabMaterialRecap[];
    recap_wage: RabWageRecap[];
    recap_tool: RabToolRecap[];

    operational: RabOperational[];

    history: RabHistory[];
}

interface RabPageProps extends PageProps {
    rab: Rab | null;

    projectOptions: SelectOption[];
    unitOptions: SelectOption[];

    filters: {
        project_id?: string;
    };
}

interface RABTabsProps {
    project_id: string;
    rab_status: string;
    detail: RabDetail[];
    recapMaterial: RabMaterialRecap[];
    recapWage: RabWageRecap[];
    recapTool: RabToolRecap[];
    operational: RabOperational[];

    unitOptions: SelectOption[];
}

interface MaterialTableProps {
    materials: RabComponentItem[];
}

interface WageTableProps {
    wages: RabComponentItem[];
}

interface ToolTableProps {
    tools: RabComponentItem[];
}

interface RabCommentForm {
    comment: string;
    action: string;
    project_id: string;
}

interface ModalRabCommentProps {
    isOpen: boolean;
    onClose: () => void;

    projectId: string;
    action: "forward" | "revision";
}

interface OperationalCostForm {
    project_id: string;
    name: string;
    unit: string;
    volume: number;
    unit_price: number;
}

interface ModalOperationalCostProps {
    isOpen: boolean;
    onClose: () => void;

    projectId: string;
    operational?: {
        id: string;
        name: string;
        unit: string;
        volume: number;
        unit_price: number;
    } | null;
    unitOptions: SelectOption[];
}

interface OperationalTableProps {
    project_id: string;
    rab_status: string;
    operationals: RabOperational[];
    unitOptions: SelectOption[];
}

export type {
    SelectOption,
    Project,
    Rab,
    RabSummary,
    RabDetail,
    RabHistory,
    RabDetailHeaderProps,
    RabMaterialRecap,
    RabWageRecap,
    RabToolRecap,
    RabPageProps,
    RABTabsProps,
    MaterialTableProps,
    WageTableProps,
    ToolTableProps,
    RabOperational,
    OperationalTableProps,
    RabCommentForm,
    ModalRabCommentProps,
    OperationalCostForm,
    ModalOperationalCostProps,
};
