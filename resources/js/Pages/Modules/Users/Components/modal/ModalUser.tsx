import { useModalForm } from "@/hooks/useModalForm";

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
    const {
        data,
        setData,
        handleSubmit,
        loading,
        serverErrors,
        isEditing,
    } = useModalForm<UserForm>({
        isOpen,
        onClose,
        initialValues: {
            name: "",
            email: "",
            role: "",
            password: "",
            is_active: "",
            email_verified_at: new Date().toISOString(),
        },
        editData: user,
        editId: user?.id,
        storeRoute: "user.store",
        updateRoute: "user.update",
    });

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
                    error={serverErrors.is_active ? "Status wajib diisi" : ""}
                    required
                    options={[
                        { value: true, label: "Aktif" },
                        { value: false, label: "Tidak Aktif" },
                    ]}
                />
            </Form>
        </Modal>
    )
}
