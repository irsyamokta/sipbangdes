import { useModalForm } from '@/hooks/useModalForm';

import { ModalUnitProps, UnitForm } from '@/types/unit';

import { Modal } from '@/Components/ui/modal';
import Form from '@/Components/form/Form';
import Input from '@/Components/form/input/InputField';
import Select from '@/Components/form/input/Select';

export const ModalUnit = ({
    isOpen,
    onClose,
    unit,
}: ModalUnitProps) => {
    const {
        data,
        setData,
        handleSubmit,
        loading,
        serverErrors,
        isEditing,
    } = useModalForm<UnitForm>({
        isOpen,
        onClose,
        initialValues: {
            name: "",
            category: "",
        },
        editData: unit,
        editId: unit?.id,
        successMessage: "Satuan berhasil disimpan",
        storeRoute: "unit.store",
        updateRoute: "unit.update",
    });

    return (
        <Modal
            isOpen={isOpen}
            onClose={onClose}
            className="max-w-175 m-4"
            title={isEditing ? "Edit Satuan" : "Tambah Satuan"}
            subtitle={isEditing ? "Ubah informasi satuan di bawah ini" : "Masukkan informasi satuan baru"}
            formId="unit-form"
            loading={loading}
        >
            <Form
                id="unit-form"
                onSubmit={handleSubmit}
                className="flex flex-col gap-4 p-4 md:p-6"
                preventEnterSubmit
            >
                {/* name */}
                <Input
                    label="Nama Satuan"
                    type="text"
                    name="name"
                    value={data.name}
                    placeholder="Contoh: m, kg, OH"
                    onChange={(e) => setData("name", e.target.value)}
                    error={serverErrors.name}
                    required
                />

                {/* category */}
                <Select
                    label="Kategori"
                    value={data.category}
                    onChange={(value) => setData("category", value)}
                    error={serverErrors.category}
                    required
                    options={[
                        { value: "panjang", label: "Panjang" },
                        { value: "luas", label: "Luas" },
                        { value: "volume", label: "Volume" },
                        { value: "berat", label: "Berat" },
                        { value: "waktu", label: "Waktu" },
                        { value: "jumlah", label: "Jumlah" },
                        { value: "tenaga kerja", label: "Tenaga Kerja" },
                        { value: "kemasan", label: "Kemasan" },
                        { value: "lainnya", label: "Lainnya" },
                    ]}
                />
            </Form>
        </Modal>
    )
}
