import { useModalForm } from '@/hooks/useModalForm';

import { useBudgetYears } from '@/hooks/useBudgetYears';

import { ModalProjectProps, ProjectForm } from '@/types/project';

import { Modal } from '@/Components/ui/modal';
import Form from '@/Components/form/Form';
import Input from '@/Components/form/input/InputField';
import Select from '@/Components/form/input/Select';

export const ModalProject = ({
    isOpen,
    onClose,
    project,
}: ModalProjectProps) => {
    const {
        data,
        setData,
        handleSubmit,
        loading,
        serverErrors,
        isEditing,
    } = useModalForm<ProjectForm>({
        isOpen,
        onClose,
        initialValues: {
            project_name: "",
            location: "",
            chairman: "",
            project_status: "draft",
            budget_year: "",
        },
        editData: project,
        editId: project?.id,
        successMessage: "Proyek berhasil disimpan",
        storeRoute: "project.store",
        updateRoute: "project.update",
    });

    const budgetYearOptions = useBudgetYears({range: 10});

    return (
        <Modal
            isOpen={isOpen}
            onClose={onClose}
            className="max-w-175 m-4"
            title={isEditing ? "Edit Proyek" : "Tambah Proyek"}
            subtitle={isEditing ? "Ubah detail proyek pembangunan desa" : "Masukkan detail proyek pembangunan desa"}
            formId="project-form"
            loading={loading}
        >
            <Form
                id="project-form"
                onSubmit={handleSubmit}
                className="flex flex-col gap-4 p-4 md:p-6"
                preventEnterSubmit
            >
                {/* Project Name */}
                <Input
                    label="Nama Proyek"
                    type="text"
                    name="project_name"
                    value={data.project_name}
                    placeholder="Contoh: Pembangunan Jalan Desa"
                    onChange={(e) => setData("project_name", e.target.value)}
                    error={serverErrors.project_name}
                    required
                />

                {/* Location */}
                <Input
                    label="Lokasi"
                    type="text"
                    name="location"
                    value={data.location}
                    placeholder="Contoh: Desa Sukamaju RT 04 RW 01"
                    onChange={(e) => setData("location", e.target.value)}
                    error={serverErrors.location}
                    required
                />

                {/* Chairman */}
                <Input
                    label="Ketua TPK"
                    type="text"
                    name="chairman"
                    value={data.chairman}
                    placeholder="Contoh: John Doe"
                    onChange={(e) => setData("chairman", e.target.value)}
                    error={serverErrors.chairman}
                    required
                />

                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {/* Budget Year */}
                    <Select
                        label="Tahun Anggaran"
                        value={data.budget_year}
                        onChange={(value) => setData("budget_year", value)}
                        error={serverErrors.budget_year}
                        required
                        options={budgetYearOptions}
                    />

                    {/* Project Status */}
                    <Select
                        label="Status"
                        value={data.project_status}
                        onChange={(value) => setData("project_status", value)}
                        error={serverErrors.project_status}
                        required
                        options={[
                            { value: "draft", label: "Draft" },
                            { value: "berjalan", label: "Berjalan" },
                            { value: "selesai", label: "Selesai" }
                        ]}
                    />
                </div>
            </Form>
        </Modal>
    )
}


