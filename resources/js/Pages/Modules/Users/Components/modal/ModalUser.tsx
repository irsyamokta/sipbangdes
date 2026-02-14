import { useState, useEffect, FormEventHandler } from "react";
import { useForm, router } from "@inertiajs/react";
import { toast } from "sonner";

import { ModalUserProps, UserForm } from "@/types/user";

import { Modal } from "@/Components/ui/modal";
import ModalTitle from "@/Components/ModalTitle";
import Form from "@/Components/form/Form"
import Button from "@/Components/ui/button/Button";
import Input from "@/Components/form/input/InputField";
import Select from "@/Components/form/input/Select";

import { AiOutlineLoading3Quarters } from "react-icons/ai";

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
                    reset();
                    setLoading(false);
                },
                onError: (errors) => {
                    reset();
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
                    reset();
                    setLoading(false);
                },
                onError: (errors) => {
                    reset();
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
        >
            <div className="no-scrollbar relative w-full max-w-200 max-h-175 overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-8">
                <ModalTitle
                    title={isEditing ? "Edit Pengguna" : "Tambah Pengguna"}
                    subtitle={isEditing ? "Ubah informasi dan hak akses pengguna di bawah ini" : "Buat informasi dan hak akses pengguna"}
                />

                <Form
                    onSubmit={handleSubmit}
                    className="flex flex-col gap-4"
                    preventEnterSubmit={true}
                >
                    {/* Name */}
                    <Input
                        label="Nama"
                        type="text"
                        name="name"
                        placeholder="Contoh: John Doe"
                        value={data.name}
                        onChange={(e) => setData("name", e.target.value)}
                        error={serverErrors.name}
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
                        error={serverErrors.role}
                        required
                        options={[
                            { value: "1", label: "Aktif" },
                            { value: "0", label: "Tidak Aktif" },
                        ]}
                    />

                    {/* Actions */}
                    <div className="flex items-center gap-3 mt-6 justify-end">
                        <Button variant="outline" onClick={onClose} disabled={loading}>
                            Batal
                        </Button>
                        <Button type="submit" disabled={loading}>
                            {loading ? (
                                <div className="flex items-center gap-2">
                                    <AiOutlineLoading3Quarters className="animate-spin text-lg" />
                                    Loading...
                                </div>
                            ) : (
                                "Simpan"
                            )}
                        </Button>
                    </div>
                </Form>
            </div>
        </Modal>
    )
}