import { useMemo } from "react";
import { useModalForm } from "@/hooks/useModalForm";

import {
    ModalProjectProgressProps,
    ProjectProgressForm,
} from "@/types/progress";

import { Modal } from "@/Components/ui/modal";
import Form from "@/Components/form/Form";
import TextArea from "@/Components/form/input/TextArea";
import Select from "@/Components/form/input/Select";
import FileInput from "@/Components/form/input/FileInput";

const ProgressModal = ({
    isOpen,
    onClose,
    project,
    totalProgress,
    progress,
}: ModalProjectProgressProps) => {
    const nextStep = [25, 50, 75, 100].find(
        (step) => step > (totalProgress || 0),
    );

    const percentageOptions = [25, 50, 75, 100].map((value) => {
        const isAchieved = value <= (totalProgress || 0);
        return {
            value,
            label: isAchieved ? `${value}% (Selesai)` : `${value}%`,
            disabled: value !== nextStep,
        };
    });

    const editData = useMemo(
        () =>
            progress
                ? {
                      percentage: String(progress.percentage),
                      description: progress.description,
                  }
                : null,
        [progress?.id, progress?.description],
    );

    const { data, setData, handleSubmit, loading, serverErrors, isEditing } =
        useModalForm<ProjectProgressForm>({
            isOpen,
            onClose,
            initialValues: {
                percentage: "",
                description: "",
                documents: [],
            },
            editData,
            editId: progress?.id ?? null,
            successMessage: "Progres berhasil disimpan",
            updateMessage: "Progres berhasil diperbarui",
            storeRoute: "progress.store",
            updateRoute: "progress.update",
            storeParams: project?.id,
            forceFormData: true,
        });

    return (
        <Modal
            isOpen={isOpen}
            onClose={onClose}
            className="max-w-175 m-4"
            title={isEditing ? "Edit Progres" : "Tambah Progres"}
            subtitle={
                isEditing
                    ? "Perbarui data progres pelaksanaan proyek"
                    : "Catat progres pelaksanaan proyek beserta dokumentasi"
            }
            formId="progress-form"
            loading={loading}
        >
            <Form
                id="progress-form"
                onSubmit={handleSubmit}
                className="flex flex-col gap-4 p-4 md:p-6"
                preventEnterSubmit
            >
                {/* Percentage */}
                {!isEditing && (
                    <Select
                        label="Persentase Progres"
                        value={data.percentage}
                        onChange={(value) => setData("percentage", value)}
                        error={serverErrors.percentage}
                        required
                        options={percentageOptions}
                    />
                )}

                {/* Description */}
                <TextArea
                    label="Keterangan"
                    value={data.description}
                    placeholder="Deskripsi progress pekerjaan..."
                    onChange={(value) => setData("description", value)}
                    error={serverErrors.description}
                    required
                />

                {/* Images */}
                <FileInput
                    label={
                        isEditing
                            ? "Tambah Dokumentasi (Opsional)"
                            : "Dokumentasi (Foto/Gambar)"
                    }
                    variant="card"
                    onChange={(e) => {
                        const files = e.target.files;
                        if (!files) return;
                        setData("documents", Array.from(files));
                    }}
                    error={serverErrors.documents}
                    multiple
                    required={!isEditing}
                />
            </Form>
        </Modal>
    );
};

export default ProgressModal;
