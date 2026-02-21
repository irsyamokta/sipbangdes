import { useModalForm } from '@/hooks/useModalForm';

import { ModalProjectProgressProps, ProjectProgressForm } from '@/types/progress';

import { Modal } from '@/Components/ui/modal';
import Form from '@/Components/form/Form';
import TextArea from '@/Components/form/input/TextArea';
import Select from '@/Components/form/input/Select';
import FileInput from '@/Components/form/input/FileInput';

export const ModalProgress = ({
    isOpen,
    onClose,
    project,
    totalProgress
}: ModalProjectProgressProps) => {
    const percentageOptions = [25, 50, 75, 100].map((value) => ({
        value,
        label: `${value}%`,
        disabled: value <= totalProgress,
    }));

    console.log(totalProgress);

    const {
        data,
        setData,
        handleSubmit,
        loading,
        serverErrors,
    } = useModalForm<ProjectProgressForm>({
        isOpen,
        onClose,
        initialValues: {
            percentage: "",
            description: "",
            documents: [],
        },
        successMessage: "Progres berhasil disimpan",
        storeRoute: "progress.store",
        storeParams: project?.id,
        forceFormData: true
    });

    return (
        <Modal
            isOpen={isOpen}
            onClose={onClose}
            className="max-w-175 m-4"
            title={"Tambah Progres"}
            subtitle={"Catat progress pelaksanaan proyek beserta dokumentasi"}
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
                <Select
                    label="Persentase Progres"
                    value={data.percentage}
                    onChange={(value) => setData("percentage", value)}
                    error={serverErrors.percentage}
                    required
                    options={percentageOptions}
                />

                {/* Decription */}
                <TextArea
                    label="Keterangan"
                    value={data.description}
                    placeholder="Deskripsi progress pekerjaan..."
                    onChange={(value) => setData("description", value)}
                    error={serverErrors.description}
                    required
                />

                {/* Documents */}
                <FileInput
                    label="Dokumentasi (Foto/Gambar)"
                    variant="card"
                    onChange={(e) => {
                        const files = e.target.files;
                        if (!files) return;
                        setData("documents", Array.from(files));
                    }}
                    error={serverErrors.documents}
                    multiple
                    required
                />
            </Form>
        </Modal>
    )
}
