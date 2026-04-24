import { useModalForm } from "@/hooks/useModalForm";

import { ModalWorkerItemProps, WorkerItemForm } from "@/types/workerCategory";

import { Modal } from "@/Components/ui/modal";
import Form from "@/Components/form/Form";
import Input from "@/Components/form/input/InputField";
import Select from "@/Components/form/input/Select";

const WorkerItemModal = ({
    isOpen,
    onClose,
    workerItem,
    categoryId,
    unitOptions,
    ahspOptions,
}: ModalWorkerItemProps) => {
    const { data, setData, handleSubmit, loading, serverErrors, isEditing } =
        useModalForm<WorkerItemForm>({
            isOpen,
            onClose,
            initialValues: {
                work_name: "",
                ahsp_id: "",
                category_id: categoryId,
                unit: "",
            },
            editData: workerItem,
            editId: workerItem?.id,
            successMessage: "Item pekerjaan berhasil disimpan",
            updateMessage: "Item pekerjaan berhasil diperbarui",
            storeRoute: "workeritem.store",
            updateRoute: "workeritem.update",
        });

    const handleAhspChange = (value: string | number | boolean) => {
        const selected = ahspOptions?.find((opt: any) => opt.value === value || opt.value === String(value));

        setData("ahsp_id", value as string);

        if (selected?.data) {
            setData("work_name", selected.data.work_name);
            setData("unit", selected.data.unit);
        }
    };

    return (
        <Modal
            isOpen={isOpen}
            onClose={onClose}
            className="max-w-175 m-4"
            title={isEditing ? "Edit Item Pekerjaan" : "Tambah Item Pekerjaan"}
            subtitle="Item pekerjaan yang dapat digunakan di TOS"
            formId="workeritem-form"
            loading={loading}
        >
            <Form
                id="workeritem-form"
                onSubmit={handleSubmit}
                className="flex flex-col gap-4 p-4 md:p-6"
            >
                {/* AHSP */}
                <Select
                    label="Referensi AHSP"
                    value={data.ahsp_id}
                    placeholder="Pilih AHSP"
                    onChange={handleAhspChange}
                    error={serverErrors.ahsp_id}
                    required
                    options={(ahspOptions ?? []).filter(
                        (ahsp: any) => ahsp.value,
                    )}
                />

                {/* Worker Name */}
                <Input
                    label="Nama Pekerjaan"
                    type="text"
                    name="work_name"
                    placeholder="Contoh: Pembersihan Lokasi"
                    value={data.work_name}
                    onChange={(e) => setData("work_name", e.target.value)}
                    error={serverErrors.work_name}
                    required
                />

                {/* Unit */}
                <Select
                    label="Satuan"
                    value={data.unit}
                    placeholder="Pilih Satuan"
                    onChange={(value) => setData("unit", value)}
                    error={serverErrors.unit}
                    required
                    options={(unitOptions ?? []).filter(
                        (unit: any) => unit.value,
                    )}
                />
            </Form>
        </Modal>
    );
};

export default WorkerItemModal;
