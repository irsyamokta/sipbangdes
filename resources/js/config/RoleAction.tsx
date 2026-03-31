import { BsSend } from "react-icons/bs";
import { LuRefreshCcw } from "react-icons/lu";
import { IoReturnUpForward } from "react-icons/io5";
import { AiOutlineFileDone } from "react-icons/ai";

type ActionType = "send" | "revision" | "forward" | "approve";

interface ActionButtonConfig {
    label: string;
    action: ActionType;
    variant?: "send" | "revision" | "approve";
    icon?: React.ReactNode;
    useModal?: boolean;
}

const roleActions: Record<string, ActionButtonConfig[]> = {
    planner: [
        {
            label: "Kirim",
            action: "send",
            variant: "send",
            icon: <BsSend />,
        },
    ],

    reviewer: [
        {
            label: "Revisi",
            action: "revision",
            variant: "revision",
            icon: <LuRefreshCcw />,
            useModal: true,
        },
        {
            label: "Teruskan",
            action: "forward",
            variant: "send",
            icon: <IoReturnUpForward size={18} />,
            useModal: true,
        },
    ],

    approver: [
        {
            label: "Revisi",
            action: "revision",
            variant: "revision",
            icon: <LuRefreshCcw />,
            useModal: true,
        },
        {
            label: "Setujui",
            action: "approve",
            variant: "approve",
            icon: <AiOutlineFileDone size={18} />,
        },
    ],
};

export default roleActions;
