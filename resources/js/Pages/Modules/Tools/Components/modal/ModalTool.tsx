import { useModalForm } from '@/hooks/useModalForm';

import { ModalToolProps, ToolForm } from '@/types/tool';

import { Modal } from '@/Components/ui/modal';
import Form from '@/Components/form/Form';
import Input from '@/Components/form/input/InputField';
import CurrencyInput from '@/Components/form/input/CurrencyInput';
import Select from '@/Components/form/input/Select';

export const ModalTool = ({
    isOpen,
    onClose,
    tool,
    units
}: ModalToolProps) => {
    const {
        data,
        setData,
        handleSubmit,
        loading,
        serverErrors,
        isEditing,
    } = useModalForm<ToolForm>({
        isOpen,
        onClose,
        initialValues: {
            name: "",
            unit: "",
            price: 0
        },
        editData: tool,
        editId: tool?.id,
        successMessage: "Alat berhasil disimpan",
        storeRoute: "tool.store",
        updateRoute: "tool.update",
    });

    return (
        <Modal
            isOpen={isOpen}
            onClose={onClose}
            className="max-w-175 m-4"
            title={isEditing ? "Edit Alat" : "Tambah Alat"}
            subtitle={isEditing ? "Ubah informasi alat di bawah ini" : "Masukkan informasi peralatan baru"}
            formId="tool-form"
            loading={loading}
        >
            <Form
                id="tool-form"
                onSubmit={handleSubmit}
                className="flex flex-col gap-4 p-4 md:p-6"
                preventEnterSubmit
            >
                {/* name */}
                <Input
                    label="Nama Alat"
                    type="text"
                    name="name"
                    value={data.name}
                    placeholder="Contoh: Molen/Concrete Mixer"
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
