import Swal from 'sweetalert2';
import withReactContent from 'sweetalert2-react-content';

const MySwal = withReactContent(Swal);

interface ConfirmDialogOptions {
    title?: string;
    text: string;
    confirmButtonText?: string;
    cancelButtonText?: string;
}

export async function confirmDialog({
    title = "Are you sure?",
    text,
    confirmButtonText = "Yes, Delete",
    cancelButtonText = "Cancel",
}: ConfirmDialogOptions) {
    const result = await MySwal.fire({
        title,
        text,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText,
        cancelButtonText,
        customClass: {
            confirmButton: 'swal2-outline-confirm',
            cancelButton: 'swal2-cancel-button',
        },
        buttonsStyling: false,
    });

    return result.isConfirmed;
};
