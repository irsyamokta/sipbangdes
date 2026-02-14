import { PageProps } from ".";

interface User {
    id: string;
    name: string;
    email: string;
    role: string;
    is_active: string;
    email_verified_at?: string;
    created_at?: string;
};

interface ModalUserProps {
    isOpen: boolean;
    onClose: () => void;
    user?: any;
};

interface UserForm {
    name: string;
    email: string;
    role: string;
    is_active: string;
    password: string;
    email_verified_at: string;
};

interface UserPageProps extends PageProps {
    users: {
        data: User[];
        links: any[];
        last_page: number;
    };
    filters: {
        search: string;
    };
}

interface UsersTableProps {
    users: User[];
    last_page: number;
    links: any[];
    onEdit: (user: User) => void;
}

export type { 
    User, 
    ModalUserProps, 
    UserForm, 
    UserPageProps, 
    UsersTableProps 
};