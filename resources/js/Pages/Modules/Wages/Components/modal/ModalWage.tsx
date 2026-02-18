import { useModalForm } from '@/hooks/useModalForm';

import { ModalWageProps, WageForm } from '@/types/wage';

import { Modal } from '@/Components/ui/modal';
import Form from '@/Components/form/Form';
import Input from '@/Components/form/input/InputField';
import CurrencyInput from '@/Components/form/input/CurrencyInput';
import Select from '@/Components/form/input/Select';

export const ModalWage = ({
    isOpen,
    onClose,
    wage,
    units
}: ModalWageProps) => {
    const {
        data,
        setData,
        handleSubmit,
        loading,
        serverErrors,
        isEditing,
    } = useModalForm<WageForm>({
        isOpen,
        onClose,
        initialValues: {
            position: "",
            unit: "",
            price: 0
        },
        editData: wage,
        editId: wage?.id,
        successMessage: "Upah berhasil disimpan",
        storeRoute: "wage.store",
        updateRoute: "wage.update",
    });

    return (
        <Modal
            isOpen={isOpen}
            onClose={onClose}
            className="max-w-175 m-4"
            title={isEditing ? "Edit Upah" : "Tambah Upah"}
            subtitle={isEditing ? "Ubah informasi upah di bawah ini" : "Masukkan informasi upah tenaga kerja baru"}
            formId="wage-form"
            loading={loading}
        >
            <Form
                id="wage-form"
                onSubmit={handleSubmit}
                className="flex flex-col gap-4 p-4 md:p-6"
                preventEnterSubmit
            >
                {/* name */}
                <Input
                    label="Jabatan/Tenaga Kerja"
                    type="text"
                    name="position"
                    value={data.position}
                    placeholder="Contoh: Tukang Batu"
                    onChange={(e) => setData("position", e.target.value)}
                    error={serverErrors.position}
                    required
                />

                {/* unit */}
                <Select
                    label="Satuan"
                    value={data.unit}
                    onChange={(value) => setData("unit", value)}
                    error={serverErrors.unit}
                    required
                    options={(units ?? [])
                        .filter((unit: any) => unit?.value)
                    }
                />

                {/* Price */}
                <CurrencyInput
                    label="Harga Satuan (Rp)"
                    name="price"
                    value={data.price}
                    onChange={(value) => setData("price", value)}
                    error={serverErrors.price}
                    isCurrency
                    required
                />
            </Form>
        </Modal>
    )
}
