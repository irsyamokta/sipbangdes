import { useModalForm } from "@/hooks/useModalForm";

import { ModalAhspProps, AhspForm } from "@/types/ahsp";

import { Modal } from "@/Components/ui/modal";
import Form from "@/Components/form/Form";
import Input from "@/Components/form/input/InputField";
import Select from "@/Components/form/input/Select";

const AhspModal = ({
    isOpen,
    onClose,
    ahsp,
    unitOptions
}: ModalAhspProps) => {
    const {
        data,
        setData,
        handleSubmit,
        loading,
        serverErrors,
        isEditing,
    } = useModalForm<AhspForm>({
        isOpen,
        onClose,
        initialValues: {
            work_name: "",
            unit: "",
        },
        editData: ahsp,
        editId: ahsp?.id,
        successMessage: "AHSP berhasil disimpan",
        updateMessage: "AHSP berhasil diperbarui",
        storeRoute: "ahsp.store",
        updateRoute: "ahsp.update",
    });

    return (
        <Modal
            isOpen={isOpen}
            onClose={onClose}
            className="max-w-175 m-4"
            title={isEditing ? "Edit Ahsp" : "Tambah Ahsp"}
            subtitle={isEditing ? "Ubah informasi Analisis Harga Satuan Pekerjaan" : "Buat informasi Analisis Harga Satuan Pekerjaan"}
            formId="ahsp-form"
            loading={loading}
        >
            <Form
                id="ahsp-form"
                onSubmit={handleSubmit}
                className="flex flex-col gap-4 p-4 md:p-6"
            >

                {/* Work Name */}
                <Input
                    label="Nama Pekerjaan"
                    type="text"
                    name="work_name"
                    placeholder="Contoh: Pasangan Batu Kali"
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
                    options={(unitOptions ?? [])
                        .filter((unit: any) => unit?.value)
                    }
                />
            </Form>
        </Modal>
    )
}

export default AhspModal;
