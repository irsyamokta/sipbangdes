import { useState, useEffect, FormEventHandler } from "react";
import { router } from "@inertiajs/react";

type CrudFormOptions<T> = {
    isOpen: boolean;
    onClose: () => void;
    initialValues: T;
    editData?: Partial<T> | null;
    editId?: string | number | null;
    storeRoute: string;
    updateRoute?: string;
    forceFormData?: boolean;
};

export function useModalForm<T extends Record<string, any>>({
    isOpen,
    onClose,
    initialValues,
    editData,
    editId,
    storeRoute,
    updateRoute,
    forceFormData = false,
}: CrudFormOptions<T>) {
    const [data, setDataState] = useState<T>(initialValues);
    const [loading, setLoading] = useState(false);
    const [serverErrors, setServerErrors] = useState<Record<string, string>>({});

    const isEditing = !!editData;

    const setData = (key: keyof T, value: any) => {
        setDataState((prev) => ({
            ...prev,
            [key]: value,
        }));
    };

    const reset = () => {
        setDataState(initialValues);
        setServerErrors({});
    };

    const hasFile = (obj: Record<string, any>) => {
        return Object.values(obj).some(
            (value) =>
                value instanceof File ||
                (Array.isArray(value) &&
                    value.some((v) => v instanceof File))
        );
    };

    const buildFormData = (obj: Record<string, any>) => {
        const formData = new FormData();

        Object.entries(obj).forEach(([key, value]) => {
            if (value === null || value === undefined) return;

            if (Array.isArray(value)) {
                value.forEach((v, index) => {
                    formData.append(`${key}[${index}]`, v);
                });
            } else {
                formData.append(key, value);
            }
        });

        return formData;
    };

    useEffect(() => {
        if (isOpen && isEditing && editData) {
            setDataState({
                ...initialValues,
                ...editData,
            });
        } else if (!isOpen) {
            reset();
        }
    }, [isOpen, isEditing, editData]);

    const handleSubmit: FormEventHandler = (e) => {
        e.preventDefault();
        setLoading(true);

        const shouldUseFormData = forceFormData || hasFile(data);
        const payload = shouldUseFormData ? buildFormData(data) : data;

        const config = {
            onSuccess: () => {
                onClose();
            },
            onError: (errors: any) => {
                setServerErrors(errors);
            },
            onFinish: () => {
                setLoading(false);
            },
        };

        if (!isEditing) {
            router.post(route(storeRoute), payload as any, config);
        } else if (isEditing && updateRoute && editId) {
            const url = route(updateRoute, editId);

            router.post(
                url,
                shouldUseFormData
                    ? (() => {
                        const fd = buildFormData(data);
                        fd.append("_method", "patch");
                        return fd;
                    })()
                    : { ...data, _method: "patch" },
                config
            );
        }
    };

    return {
        data,
        setData,
        reset,
        handleSubmit,
        loading,
        serverErrors,
        isEditing,
    };
}
