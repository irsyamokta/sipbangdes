import { useState } from "react";
import { router } from "@inertiajs/react";
import { toast } from "sonner";

import Button from "@/Components/ui/button/Button";
import { ConfirmationDialog } from "@/Components/ConfirmationDialog";
import RabCommentModal from "../modal/RabCommentModal";

import roleActions from "@/config/RoleAction";
import { confirmConfig, successMessage } from "@/config/ConfirmDialog";

interface Props {
    role: "planner" | "reviewer" | "approver";
    projectId: string;
    status: string;
}

type ActionType = "send" | "forward" | "approve" | "revision";

const RabActionButtons = ({ role, projectId, status }: Props) => {
    const [selectedAction, setSelectedAction] = useState<ActionType | null>(null);

    if (!projectId) return null;

    // Check if action is disabled
    const isDisabled = (action: ActionType) => {
        switch (role) {
            case "planner":
                return !(status === "draft" || status === "revision");

            case "reviewer":
                return status !== "submitted";

            case "approver":
                return status !== "reviewed";

            default:
                return true;
        }
    };

    const handleClick = async (action: ActionType, useModal?: boolean) => {
        // Open modal
        if (useModal) {
            setSelectedAction(action);
            return;
        }

        // Confirm
        if (["send", "forward", "approve"].includes(action)) {
            const confirm = await ConfirmationDialog({
                title: confirmConfig[action].title,
                text: confirmConfig[action].text,
                confirmButtonText: "Ya, lanjutkan",
                cancelButtonText: "Batal",
            });

            if (!confirm) return;
        }

        // Process
        router.post(
            route("rab.action"),
            {
                action,
                project_id: projectId,
            },
            {
                onSuccess: () => {
                    toast.success(successMessage[action]);
                },
                onError: (errors) => {
                    toast.error(errors[0]);
                },
            }
        );
    };

    const actions = roleActions[role] ?? [];

    return (
        <>
            {/* Buttons */}
            <div className="flex gap-3">
                {actions.map((btn, index) => (
                    <Button
                        key={index}
                        variant={btn.variant}
                        startIcon={btn.icon}
                        className="w-full md:w-auto"
                        onClick={() => handleClick(btn.action, btn.useModal)}
                        disabled={isDisabled(btn.action)}
                    >
                        {btn.label}
                    </Button>
                ))}
            </div>

            {/* Modal */}
            <RabCommentModal
                isOpen={!!selectedAction}
                onClose={() => setSelectedAction(null)}
                projectId={projectId}
                action={selectedAction as any}
            />
        </>
    );
};

export default RabActionButtons;
