import { useModalForm } from "@/hooks/useModalForm";

import { ModalAhspMaterialProps, AhspMaterialForm } from "@/types/ahsp";

import { Modal } from "@/Components/ui/modal";
import Form from "@/Components/form/Form";
import InputCurrency from "@/Components/form/input/CurrencyInput";
import Select from "@/Components/form/input/Select";

const AhspMaterialModal = ({
    isOpen,
    onClose,
    ahspId,
    material,
    materialOptions
}: ModalAhspMaterialProps) => {
    const {
        data,
        setData,
        handleSubmit,
        loading,
        serverErrors,
        isEditing,
    } = useModalForm<AhspMaterialForm>({
        isOpen,
        onClose,
        initialValues: {
            ahsp_id: ahspId,
            material_id: "",
            coefficient: 0,
        },
        editData: material,
        editId: material?.id,
        successMessage: "Material berhasil disimpan",
        updateMessage: "Material berhasil diperbarui",
        storeRoute: "ahsp.material.store",
        updateRoute: "ahsp.material.update",
    });

    return (
        <Modal
            isOpen={isOpen}
            onClose={onClose}
            className="max-w-175 m-4"
            title={isEditing ? "Edit Material" : "Tambah Material"}
            formId="ahsp-material-form"
            loading={loading}
        >
            <Form
                id="ahsp-material-form"
                onSubmit={handleSubmit}
                className="flex flex-col gap-4 p-4 md:p-6"
            >
                {/* Material */}
                <Select
                    label="Pilih Material"
                    value={data.material_id}
                    placeholder="Pilih Material"
                    onChange={(value) => setData("material_id", value)}
                    error={serverErrors.material_id}
                    required
                    options={(materialOptions ?? [])
                        .filter((material: any) => material.value)
                    }
                />

                {/* Coefficient */}
                <InputCurrency
                    label="Koefisien"
                    type="text"
                    name="coefficient"
                    value={data.coefficient}
                    onChange={(value) => setData("coefficient", value)}
                    error={serverErrors.coefficient}
                    required
                />
            </Form>
        </Modal>
    )
}

export default AhspMaterialModal;
