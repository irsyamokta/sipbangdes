import { PageProps } from ".";

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
    tool?: any;
    units: {
        values: string;
        labels: string;
        filter: any;
    }
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
    units: {
        values: string;
        labels: string;
    }
    filters: {
        search: string;
    }
};

interface ToolsTableProps {
    tools: Tool[];
    last_page: number;
    links: any[];
    onEdit: (tool: Tool) => void;
};

export type {
    Tool,
    ModalToolProps,
    ToolForm,
    ToolPageProps,
    ToolsTableProps
};
