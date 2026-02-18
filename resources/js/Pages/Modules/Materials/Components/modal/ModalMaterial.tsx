import { useModalForm } from '@/hooks/useModalForm';

import { ModalMaterialProps, MaterialForm } from '@/types/material';

import { Modal } from '@/Components/ui/modal';
import Form from '@/Components/form/Form';
import Input from '@/Components/form/input/InputField';
import CurrencyInput from '@/Components/form/input/CurrencyInput';
import Select from '@/Components/form/input/Select';

export const ModalMaterial = ({
    isOpen,
    onClose,
    material,
    units
}: ModalMaterialProps) => {
    const {
        data,
        setData,
        handleSubmit,
        loading,
        serverErrors,
        isEditing,
    } = useModalForm<MaterialForm>({
        isOpen,
        onClose,
        initialValues: {
            name: "",
            unit: "",
            price: 0
        },
        editData: material,
        editId: material?.id,
        storeRoute: "material.store",
        updateRoute: "material.update",
    });

    return (
        <Modal
            isOpen={isOpen}
            onClose={onClose}
            className="max-w-175 m-4"
            title={isEditing ? "Edit Material" : "Tambah Material"}
            subtitle={isEditing ? "Ubah informasi material di bawah ini" : "Masukkan informasi material baru"}
            formId="material-form"
            loading={loading}
        >
            <Form
                id="material-form"
                onSubmit={handleSubmit}
                className="flex flex-col gap-4 p-4 md:p-6"
                preventEnterSubmit
            >
                {/* name */}
                <Input
                    label="Nama Material"
                    type="text"
                    name="name"
                    value={data.name}
                    placeholder="Contoh: Semen Portland (50 kg)"
                    onChange={(e) => setData("name", e.target.value)}
                    error={serverErrors.name}
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
