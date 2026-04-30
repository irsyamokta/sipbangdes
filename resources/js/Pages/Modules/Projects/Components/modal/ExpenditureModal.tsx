import { useModalForm } from '@/hooks/useModalForm';

import { ModalProjectExpenditureProps, ProjectExpenditureForm } from '@/types/progress';

import { Modal } from '@/Components/ui/modal';
import Form from '@/Components/form/Form';
import Input from '@/Components/form/input/InputField';
import TextArea from '@/Components/form/input/TextArea';
import NumberInput from '@/Components/form/input/NumberInput';
import DatePicker from '@/Components/form/input/DatePicker';

const ExpenditureModal = ({
    isOpen,
    onClose,
    project,
    expenditure,
    remainingBudget
}: ModalProjectExpenditureProps) => {
    const {
        data,
        setData,
        handleSubmit,
        loading,
        serverErrors,
        isEditing,
    } = useModalForm<ProjectExpenditureForm>({
        isOpen,
        onClose,
        initialValues: {
            description: "",
            nominal: 0,
            date: "",
            information: "",
        },
        editData: expenditure,
        editId: expenditure?.id,
        successMessage: "Pengeluaran berhasil disimpan",
        updateMessage: "Pengeluaran berhasil diperbarui",
        storeRoute: "expenditure.store",
        updateRoute: "expenditure.update",
        storeParams: project?.id,
    });

    return (
        <Modal
            isOpen={isOpen}
            onClose={onClose}
            className="max-w-175 m-4"
            title={isEditing ? "Edit Pengeluaran" : "Tambah Pengeluaran"}
            subtitle={isEditing ? "Ubah realisasi pengeluaran anggaran proyek" : "Catat realisasi pengeluaran anggaran proyek"}
            formId="expenditure-form"
            loading={loading}
        >
            <Form
                id="expenditure-form"
                onSubmit={handleSubmit}
                className="flex flex-col gap-4 p-4 md:p-6"
                preventEnterSubmit
            >
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    
                    {/* Description */}
                    <Input
                        label="Uraian"
                        type="text"
                        name="description"
                        value={data.description}
                        placeholder="Contoh: Pembelian Semen"
                        onChange={(e) => setData("description", e.target.value)}
                        error={serverErrors.description}
                        required
                    />

                    {/* Date */}
                    <DatePicker
                        label="Tanggal"
                        name="date"
                        value={data.date}
                        placeholder="Pilih tanggal"
                        onChange={(value) => setData("date", value)}
                        error={serverErrors.date}
                        required
                    />
                </div>

                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    {/* Nominal */}
                    <NumberInput
                        label="Nominal"
                        name="nominal"
                        value={data.nominal}
                        onChange={(value) => setData("nominal", value)}
                        error={serverErrors.nominal}
                        isCurrency
                        required
                    />

                    {/* Remaining Budget */}
                    <NumberInput
                        label="Sisa Anggaran"
                        value={remainingBudget || 0}
                        onChange={() => {}}
                        isCurrency
                        disabled
                    />
                </div>

                {/* Information */}
                <TextArea
                    label="Keterangan"
                    value={data.information || ""}
                    placeholder="Catatan tambahan..."
                    onChange={(value) => setData("information", value)}
                    error={serverErrors.information}
                    required
                />
            </Form>
        </Modal>
    )
}

export default ExpenditureModal;
