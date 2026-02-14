import { useState } from "react";
import { router } from "@inertiajs/react";
import { toast } from "sonner";

import { ConfirmationDialog } from "@/Components/ConfirmationDialog";

interface DeleteOptions {
    routeName: string;
    confirmTitle?: string;
    confirmText?: string;
    successMessage?: string;
    errorMessage?: string;
}

export function useDelete(options: DeleteOptions) {
    const {
        routeName,
        confirmTitle = "Apakah Anda yakin?",
        confirmText = "Data yang dihapus tidak dapat dikembalikan!",
        successMessage = "Berhasil dihapus",
        errorMessage = "Gagal menghapus data",
    } = options;

    const [deletingId, setDeletingId] = useState<string | null>(null);

    const handleDelete = async (id: string) => {
        const confirmed = await ConfirmationDialog({
            title: confirmTitle,
            text: confirmText,
            confirmButtonText: "Ya, Hapus",
            cancelButtonText: "Batal",
        });

        if (!confirmed) return;

        setDeletingId(id);

        router.delete(route(routeName, id), {
            onSuccess: () => {
                toast.success(successMessage);
                setDeletingId(null);
            },
            onError: () => {
                toast.error(errorMessage);
                setDeletingId(null);
            },
        });
    };

    return {
        handleDelete,
        deletingId,
    };
}
