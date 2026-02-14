import { usePage } from "@inertiajs/react";

export default function usePermission() {
    const { auth }: any = usePage().props;

    const permissions: string[] = auth?.permissions || [];

    const can = (permission: string) => {
        return permissions.includes(permission);
    };

    return { can };
}
