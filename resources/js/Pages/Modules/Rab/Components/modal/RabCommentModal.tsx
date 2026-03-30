import { useModalForm } from "@/hooks/useModalForm";

import { RabCommentForm, ModalRabCommentProps } from "@/types/rab";

import { Modal } from "@/Components/ui/modal";
import Form from "@/Components/form/Form";
import TextArea from "@/Components/form/input/TextArea";

import modalConfig from "@/config/RabModalConfig";
import { successMessage } from "@/config/ConfirmDialog";

export const RabCommentModal = ({
    isOpen,
    onClose,
    projectId,
    action,
}: ModalRabCommentProps) => {
    if (!action) return null;
    const config = modalConfig[action];

    const {
        data,
        setData,
        handleSubmit,
        loading,
        serverErrors,
    } = useModalForm<RabCommentForm>({
        isOpen,
        onClose,
        initialValues: {
            comment: "",
            action,
            project_id: projectId,
        },
        storeRoute: "rab.action",
        successMessage: successMessage[action],
    });

    return (
        <Modal
            isOpen={isOpen}
            onClose={onClose}
            className="max-w-175 m-4"
            title={config.title}
            subtitle={config.subtitle}
            formId="rab-comment-form"
            loading={loading}
        >
            <Form
                id="rab-comment-form"
                onSubmit={handleSubmit}
                className="flex flex-col gap-4 p-4 md:p-6"
            >
                {/* Comment */}
                <TextArea
                    label="Catatan/Komentar"
                    name="comment"
                    placeholder="Tulis catatan di sini..."
                    value={data.comment}
                    onChange={(value) => setData("comment", value)}
                    error={serverErrors.comment}
                    required
                />
            </Form>
        </Modal>
    );
};
