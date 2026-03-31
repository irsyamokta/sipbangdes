import { FiSend, FiCornerUpRight, FiRotateCcw, FiUser } from "react-icons/fi";
import { AiOutlineFileDone } from "react-icons/ai";

const actionConfig: Record<string, any> = {
    send: {
        label: "Dikirim",
        variant: "text-blue-700 border-blue-700",
        color: "primary",
        icon: <FiSend size={14} />,
    },
    forward: {
        label: "Diteruskan",
        variant: "text-blue-700 border-blue-700",
        color: "primary",
        icon: <FiCornerUpRight size={14} />,
    },
    revision: {
        label: "Revisi",
        variant: "text-warning-700 border-warning-700",
        color: "warning",
        icon: <FiRotateCcw size={14} />,
    },
    approve: {
        label: "Disetujui",
        variant: "text-green-700 border-green-700",
        color: "success",
        icon: <AiOutlineFileDone size={18} />,
    },
};

export default actionConfig;
