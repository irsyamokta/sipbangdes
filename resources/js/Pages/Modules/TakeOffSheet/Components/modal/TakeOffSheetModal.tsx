import { useModalForm } from "@/hooks/useModalForm";

import { ModalTakeOffSheetProps, TakeOffSheetForm } from "@/types/tos";

import { Modal } from "@/Components/ui/modal";
import Form from "@/Components/form/Form";
import Input from "@/Components/form/input/InputField";
import NumberInput from "@/Components/form/input/NumberInput";
import Select from "@/Components/form/input/Select";

const TakeOffSheetModal = ({
    isOpen,
    onClose,
    takeOffSheet,
    projectOptions,
    ahspOptions,
    workerCategoryOptions,
    unitOptions,
}: ModalTakeOffSheetProps) => {
    const { data, setData, handleSubmit, loading, serverErrors, isEditing } =
        useModalForm<TakeOffSheetForm>({
            isOpen,
            onClose,
            initialValues: {
                project_id: "",
                ahsp_id: "",
                worker_category_id: "",
                work_name: "",
                unit: "",
                volume: 0,
            },
            editData: takeOffSheet,
            editId: takeOffSheet?.id,
            successMessage: "Take Off Sheet berhasil disimpan",
            updateMessage: "Take Off Sheet berhasil diperbarui",
            errorMessage: "Proyek sudah disetujui",
            storeRoute: "tos.store",
            updateRoute: "tos.update",
        });

    const filteredAhspOptions = ahspOptions?.filter(
        (ahsp) => ahsp.category_id === data.worker_category_id,
    );

    const handleAhspChange = (value: string | number | boolean) => {
        const selected = filteredAhspOptions?.find((opt: any) => opt.value === value || opt.value === String(value));

        setData("ahsp_id", value as string);

        if (selected?.data) {
            setData("work_name", selected.data.work_name);
            setData("unit", selected.data.unit);
        }
    };

    const isAhspSelected = !!data.ahsp_id;
    
    return (
        <Modal
            isOpen={isOpen}
            onClose={onClose}
            className="max-w-175 m-4"
            title={isEditing ? "Edit Item TOS" : "Tambah Item TOS"}
            subtitle={
                isEditing
                    ? "Ubah data pengukuran volume pekerjaan"
                    : "Masukkan data pengukuran volume pekerjaan"
            }
            formId="tos-form"
            loading={loading}
        >
            <Form
                id="tos-form"
                onSubmit={handleSubmit}
                className="flex flex-col gap-4 p-4 md:p-6"
                preventEnterSubmit
            >
                {/* Project */}
                <Select
                    label="Proyek"
                    placeholder="Pilih proyek"
                    value={data.project_id}
                    onChange={(value) => setData("project_id", value)}
                    options={projectOptions ?? []}
                    error={serverErrors.project_id}
                    required
                />

                {/* Category */}
                <Select
                    label="Kategori Pekerjaan"
                    placeholder="Pilih Kategori Pekerjaan"
                    value={data.worker_category_id}
                    onChange={(value) => setData("worker_category_id", value)}
                    options={workerCategoryOptions ?? []}
                    error={serverErrors.worker_category_id}
                    required
                />

                {/* AHSP */}
                <Select
                    label="AHSP"
                    placeholder="Pilih AHSP"
                    value={data.ahsp_id}
                    onChange={handleAhspChange}
                    options={filteredAhspOptions ?? []}
                    error={serverErrors.ahsp_id}
                    required
                />

                {/* Worker Name */}
                <Input
                    label="Nama Pekerjaan"
                    placeholder="Contoh: Pembersihan Lokasi"
                    value={data.work_name}
                    onChange={(e) => setData("work_name", e.target.value)}
                    error={serverErrors.work_name}
                    required
                    disabled={!isAhspSelected}
                />

                {/* Volume & Unit */}
                <div className="grid md:grid-cols-2 gap-4">
                    <NumberInput
                        label="Volume"
                        placeholder="Masukkan Volume"
                        value={data.volume}
                        onChange={(value) => setData("volume", value)}
                        error={serverErrors.volume}
                        required
                        disabled={!isAhspSelected}
                    />

                    <Select
                        label="Satuan"
                        placeholder="Pilih Satuan"
                        value={data.unit}
                        onChange={(value) => setData("unit", value as string)}
                        options={unitOptions ?? []}
                        error={serverErrors.unit}
                        required
                        disabled={!isAhspSelected}
                    />
                </div>
            </Form>
        </Modal>
    );
};

export default TakeOffSheetModal;
