import { useState, useEffect, FormEventHandler } from "react";
import { useForm, router } from "@inertiajs/react";
import { toast } from "sonner";

import { ModalUserProps, UserForm } from "@/types/user";

import { Modal } from "@/Components/ui/modal";
import Form from "@/Components/form/Form"
import Input from "@/Components/form/input/InputField";
import Select from "@/Components/form/input/Select";

export const ModalUser = ({
    isOpen,
    onClose,
    user,
}: ModalUserProps) => {
    const [loading, setLoading] = useState(false);
    const [serverErrors, setServerErrors] = useState<Record<string, string>>({});

    const isEditing = !!user;

    const { data, setData, reset, errors } = useForm<UserForm>({
        name: "",
        email: "",
        role: "",
        password: "",
        is_active: "",
        email_verified_at: new Date().toISOString(),
    });

    useEffect(() => {
        if (isOpen && isEditing && user) {
            setData({
                name: user.name || "",
                email: user.email || "",
                role: user.role || "",
                is_active: user.is_active ? "1" : "0",
                email_verified_at: user.email_verified_at || "",
            });
        } else if (!isOpen) {
            reset();
            setServerErrors({});
        }
    }, [isOpen, user, isEditing, setData, reset]);

    const handleSubmit: FormEventHandler = (e) => {
        e.preventDefault();
        setLoading(true);

        if (!isEditing) {
            router.post(route("user.store"), data as any, {
                onSuccess: () => {
                    toast.success("Pengguna berhasil ditambahkan!");
                    onClose();
                },
                onFinish: () => {
                    setLoading(false);
                },
                onError: (errors) => {
                    setServerErrors(errors);
                    setLoading(false);
                },
            });
        } else {
            router.patch(route("user.update", user?.id), data as any, {
                onSuccess: () => {
                    toast.success("Pengguna berhasil diperbarui!");
                    onClose();
                },
                onFinish: () => {
                    setLoading(false);
                },
                onError: (errors) => {
                    setServerErrors(errors);
                    setLoading(false);
                },
            });
        }
    }

    return (
        <Modal
            isOpen={isOpen}
            onClose={onClose}
            className="max-w-175 m-4"
            title={isEditing ? "Edit Pengguna" : "Tambah Pengguna"}
            subtitle={isEditing ? "Ubah informasi dan hak akses pengguna di bawah ini" : "Buat informasi dan hak akses pengguna"}
            formId="user-form"
            loading={loading}
        >
            <Form
                id="user-form"
                onSubmit={handleSubmit}
                className="flex flex-col gap-4 p-4 md:p-6"
                preventEnterSubmit
            >
                {/* Name */}
                <Input
                    label="Nama"
                    type="text"
                    name="name"
                    placeholder="Contoh: John Doe"
                    value={data.name}
                    onChange={(e) => setData("name", e.target.value)}
                    error={serverErrors.name ? "Nama wajib diisi" : ""}
                    required
                />

                {/* Email */}
                <Input
                    label="Email"
                    type="email"
                    name="email"
                    placeholder="Contoh: john@email.com"
                    value={data.email}
                    onChange={(e) => setData("email", e.target.value)}
                    error={serverErrors.email}
                    required
                />

                {/* Password */}
                <Input
                    label="Password"
                    type="password"
                    name="password"
                    placeholder="********"
                    value={data.password}
                    onChange={(e) => setData("password", e.target.value)}
                    error={serverErrors.password}
                    required={!isEditing}
                    enablePasswordValidation
                />

                {/* Role */}
                <Select
                    label="Role"
                    value={data.role}
                    onChange={(value) => setData("role", value)}
                    error={serverErrors.role}
                    required
                    options={[
                        { value: "admin", label: "Admin" },
                        { value: "planner", label: "Planner" },
                        { value: "reviewer", label: "Reviewer" },
                        { value: "approver", label: "Approver" },
                    ]}
                />

                {/* Status */}
                <Select
                    label="Status"
                    value={data.is_active}
                    onChange={(value) => setData("is_active", value)}
                    error={serverErrors.is_active ? "Status wajib diisi" : ""}
                    required
                    options={[
                        { value: "1", label: "Aktif" },
                        { value: "0", label: "Tidak Aktif" },
                    ]}
                />
            </Form>
        </Modal>
    )
}
