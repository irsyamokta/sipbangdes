import { useModalForm } from "@/hooks/useModalForm";

import { ModalAhspWageProps, AhspWageForm } from "@/types/ahsp";

import { Modal } from "@/Components/ui/modal";
import Form from "@/Components/form/Form";
import InputCurrency from "@/Components/form/input/CurrencyInput";
import Select from "@/Components/form/input/Select";

export const ModalAhspWage = ({
    isOpen,
    onClose,
    ahspId,
    wage,
    wageOptions
}: ModalAhspWageProps) => {
    const {
        data,
        setData,
        handleSubmit,
        loading,
        serverErrors,
        isEditing,
    } = useModalForm<AhspWageForm>({
        isOpen,
        onClose,
        initialValues: {
            ahsp_id: ahspId,
            wage_id: "",
            coefficient: 0,
        },
        editData: wage,
        editId: wage?.id,
        successMessage: "Upah berhasil disimpan",
        storeRoute: "ahsp.wage.store",
        updateRoute: "ahsp.wage.update",
    });

    return (
        <Modal
            isOpen={isOpen}
            onClose={onClose}
            className="max-w-175 m-4"
            title={isEditing ? "Edit Upah" : "Tambah Upah"}
            formId="ahsp-wage-form"
            loading={loading}
        >
            <Form
                id="ahsp-wage-form"
                onSubmit={handleSubmit}
                className="flex flex-col gap-4 p-4 md:p-6"
            >
                {/* Wage */}
                <Select
                    label="Pilih Upah"
                    value={data.wage_id}
                    onChange={(value) => setData("wage_id", value)}
                    error={serverErrors.wage_id}
                    required
                    options={(wageOptions ?? [])
                        .filter((wage: any) => wage.value)
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
