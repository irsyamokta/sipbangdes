import { useModalForm } from "@/hooks/useModalForm";

import { ModalAhspToolProps, AhspToolForm } from "@/types/ahsp";

import { Modal } from "@/Components/ui/modal";
import Form from "@/Components/form/Form";
import InputCurrency from "@/Components/form/input/CurrencyInput";
import Select from "@/Components/form/input/Select";

export const ModalAhspTool = ({
    isOpen,
    onClose,
    ahspId,
    tool,
    toolOptions
}: ModalAhspToolProps) => {
    const {
        data,
        setData,
        handleSubmit,
        loading,
        serverErrors,
        isEditing,
    } = useModalForm<AhspToolForm>({
        isOpen,
        onClose,
        initialValues: {
            ahsp_id: ahspId,
            tool_id: "",
            coefficient: 0,
        },
        editData: tool,
        editId: tool?.id,
        successMessage: "Alat berhasil disimpan",
        storeRoute: "ahsp.tool.store",
        updateRoute: "ahsp.tool.update",
    });

    return (
        <Modal
            isOpen={isOpen}
            onClose={onClose}
            className="max-w-175 m-4"
            title={isEditing ? "Edit Alat" : "Tambah Alat"}
            formId="ahsp-tool-form"
            loading={loading}
        >
            <Form
                id="ahsp-tool-form"
                onSubmit={handleSubmit}
                className="flex flex-col gap-4 p-4 md:p-6"
            >
                {/* Tool */}
                <Select
                    label="Pilih Alat"
                    value={data.tool_id}
                    onChange={(value) => setData("tool_id", value)}
                    error={serverErrors.tool_id}
                    required
                    options={(toolOptions ?? [])
                        .filter((tool: any) => tool.value)
                    }
                />

                {/* Coeficient */}
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
