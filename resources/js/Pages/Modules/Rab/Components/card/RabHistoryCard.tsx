import { RabHistory } from "@/types/rab";
import Badge from "@/Components/ui/badge/Badge";

import actionConfig from "@/config/ActionConfig";
import { formatDateTime } from "@/utils/formatDate";

import { BsChatLeftText } from "react-icons/bs";
import { FiUser } from "react-icons/fi";

interface Props {
    history: RabHistory[];
}

const roleLabelMap: Record<string, string> = {
    planner: "Kaur Perencanaan",
    reviewer: "Sekretaris Desa",
    approver: "Kepala Desa",
};

const getRoleLabel = (role: string) => {
    return roleLabelMap[role] || role;
};

export default function RabHistoryCard({ history }: Props) {
    return (
        <div className="w-full rounded-2xl border border-gray-300 bg-white p-6">
            {/* Header */}
            <div className="flex items-center gap-2 mb-6">
                <BsChatLeftText className="text-gray-600" />
                <h3 className="text-gray-900 text-lg font-semibold">
                    Riwayat & Catatan RAB
                </h3>
            </div>

            {history.length === 0 ? (
                <p className="text-gray-600 text-center text-sm py-10">
                    Belum ada riwayat atau catatan untuk RAB ini.
                </p>
            ) : (
                <div className="space-y-6">
                    {history.map((item, index) => {
                        const config =
                            actionConfig[item.action] || actionConfig.send;

                        return (
                            <div key={item.id} className="flex gap-4">
                                {/* Timeline */}
                                <div className="flex flex-col items-center">
                                    <div
                                        className={`w-8 h-8 flex items-center justify-center rounded-full border ${config.variant}`}
                                    >
                                        {config.icon}
                                    </div>

                                    {index !== history.length - 1 && (
                                        <div className="w-px flex-1 bg-gray-200 mt-1" />
                                    )}
                                </div>

                                {/* Content */}
                                <div className="flex-1">
                                    {/* Badge + Date */}
                                    <div className="flex items-center gap-3 mb-2">
                                        <Badge
                                            color={config.color}
                                        >
                                            {config.label}
                                        </Badge>

                                        <span className="text-sm text-gray-500">
                                            {formatDateTime(item.date)}
                                        </span>
                                    </div>

                                    {/* User */}
                                    <div className="flex items-center gap-2 text-gray-500 text-sm mb-2">
                                        <FiUser size={16} />
                                        <span className="font-bold text-gray-900">
                                            {item.user}
                                        </span>
                                        <span className="text-gray-500">
                                            ({getRoleLabel(item.role)})
                                        </span>
                                    </div>

                                    {/* Comment */}
                                    {item.comment && (
                                        <div className="bg-gray-100 text-gray-700 text-sm px-3 py-2 rounded-lg">
                                            {item.comment}
                                        </div>
                                    )}
                                </div>
                            </div>
                        );
                    })}
                </div>
            )}
        </div>
    );
}
