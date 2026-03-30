import { useModalForm } from "@/hooks/useModalForm";

import { ModalOperationalCostProps, OperationalCostForm } from "@/types/rab";

import { Modal } from "@/Components/ui/modal";
import Form from "@/Components/form/Form";
import Input from "@/Components/form/input/InputField";
import Select from "@/Components/form/input/Select";
import CurrencyInput from "@/Components/form/input/CurrencyInput";

export const OperationalCostModal = ({
    isOpen,
    onClose,
    projectId,
    operational,
    unitOptions
}: ModalOperationalCostProps) => {

    const {
        data,
        setData,
        handleSubmit,
        loading,
        serverErrors,
        isEditing,
    } = useModalForm<OperationalCostForm>({
        isOpen,
        onClose,
        initialValues: {
            project_id: projectId,
            name: "",
            unit: "",
            volume: 0,
            unit_price: 0,
        },
        editData: operational,
        editId: operational?.id,
        successMessage: "Biaya operasional berhasil disimpan",
        storeRoute: "operational.store",
        updateRoute: "operational.update",
    });

    return (
        <Modal
            isOpen={isOpen}
            onClose={onClose}
            className="max-w-175 m-4"
            title={isEditing ? "Edit Biaya Operasional" : "Tambah Biaya Operasional"}
            subtitle={
                isEditing
                    ? "Ubah data biaya operasional"
                    : "Tambahkan biaya operasional proyek"
            }
            formId="operational-form"
            loading={loading}
        >
            <Form
                id="operational-form"
                onSubmit={handleSubmit}
                className="flex flex-col gap-4 p-4 md:p-6"
            >

                {/* Name */}
                <Input
                    label="Nama Biaya"
                    type="text"
                    name="name"
                    placeholder="Contoh: Transportasi"
                    value={data.name}
                    onChange={(e) => setData("name", e.target.value)}
                    error={serverErrors.name}
                    required
                />

                {/* Unit */}
                <Select
                    label="Satuan"
                    value={data.unit}
                    onChange={(value) => setData("unit", value)}
                    error={serverErrors.unit}
                    required
                    options={(unitOptions ?? [])
                        .filter((unit: any) => unit?.value)
                    }
                />

                {/* Volume */}
                <CurrencyInput
                    label="Kebutuhan"
                    name="volume"
                    value={data.volume}
                    onChange={(value) => setData("volume", value)}
                    error={serverErrors.volume}
                    required
                />

                {/* Unit Price */}
                <CurrencyInput
                    label="Harga Satuan"
                    name="unit_price"
                    value={data.unit_price}
                    onChange={(value) => setData("unit_price", value)}
                    error={serverErrors.unit_price}
                    isCurrency
                    required
                />
            </Form>
        </Modal>
    );
};
