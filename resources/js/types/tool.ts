import { PageProps } from ".";

interface SelectOption {
    value: string;
    label: string;
}

interface Tool {
    id: string;
    code: string;
    name: string;
    unit: string;
    price: number;
    created_at?: string;
    updated_at?: string;
};

interface ModalToolProps {
    isOpen: boolean;
    onClose: () => void;
    tool?: Tool | null;
    unitOptions: SelectOption[];
};

interface ToolForm {
    name: string;
    unit: string;
    price: number;
};

interface ToolPageProps extends PageProps {
    tools: {
        data: Tool[];
        links: any[];
        last_page: number;
    }
    unitOptions: SelectOption[];
    filters: {
        search: string;
    }
};

interface ToolsTableProps {
    tools: Tool[];
    last_page: number;
    links: any[];
    filters: {
        search: string;
    }
    onEdit: (tool: Tool) => void;
};

export type {
    Tool,
    ModalToolProps,
    ToolForm,
    ToolPageProps,
    ToolsTableProps
};
