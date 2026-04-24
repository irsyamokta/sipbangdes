import { useModalForm } from "@/hooks/useModalForm";

import { ModalWorkerCategoryProps, WorkerCategoryForm } from "@/types/workerCategory";

import { Modal } from "@/Components/ui/modal";
import Form from "@/Components/form/Form";
import Input from "@/Components/form/input/InputField";
import TextArea from "@/Components/form/input/TextArea";

const WorkerCategoryModal = ({
    isOpen,
    onClose,
    workerCategory
}: ModalWorkerCategoryProps) => {
    const {
        data,
        setData,
        handleSubmit,
        loading,
        serverErrors,
        isEditing,
    } = useModalForm<WorkerCategoryForm>({
        isOpen,
        onClose,
        initialValues: {
            name: "",
            description: "",
        },
        editData: workerCategory,
        editId: workerCategory?.id,
        successMessage: "Kategori pekerjaan berhasil disimpan",
        updateMessage: "Kategori pekerjaan berhasil diperbarui",
        storeRoute: "workercategory.store",
        updateRoute: "workercategory.update",
    });

    return (
        <Modal
            isOpen={isOpen}
            onClose={onClose}
            className="max-w-175 m-4"
            title={isEditing ? "Edit Kategori Pekerjaan" : "Tambah Kategori Pekerjaan"}
            subtitle="Kategori untuk mengelompokkan jenis pekerjaan"
            formId="workercategory-form"
            loading={loading}
        >
            <Form
                id="workercategory-form"
                onSubmit={handleSubmit}
                className="flex flex-col gap-4 p-4 md:p-6"
            >
                {/* Category Name */}
                <Input
                    label="Nama Kategori"
                    type="text"
                    name="name"
                    placeholder="Contoh: Pekerjaan Persiapan"
                    value={data.name}
                    onChange={(e) => setData("name", e.target.value)}
                    error={serverErrors.name}
                    required
                />

                {/* Description */}
                <TextArea
                    label="Deskripsi"
                    name="description"
                    placeholder="Deskripsi singkat kategori..."
                    value={data.description}
                    maxLength={120}
                    onChange={(value) => setData("description", value)}
                    error={serverErrors.description}
                    required
                />
            </Form>
        </Modal>
    )
}

export default WorkerCategoryModal;
