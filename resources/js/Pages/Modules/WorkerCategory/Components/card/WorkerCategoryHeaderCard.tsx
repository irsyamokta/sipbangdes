import usePermission from "@/hooks/usePermission";

import { WorkerCategoryHeaderCardProps } from "@/types/workerCategory";

import Button from "@/Components/ui/button/Button";
import Badge from "@/Components/ui/badge/Badge";

import { capitalizeEachWord, capitalizedFirst } from "@/utils/capitalize";

import { LuPencil, LuTrash2 } from "react-icons/lu";
import { GoChevronRight } from "react-icons/go";

const WorkerCategoryHeaderCard = ({
    workerCategory,
    open,
    deletingId,
    toggle,
    onEdit,
    onDelete,
}: WorkerCategoryHeaderCardProps) => {
    const { can } = usePermission();

    return (
        <div
            onClick={toggle}
            className={`
                w-full bg-white px-4 py-5 hover:bg-gray-50 transition cursor-pointer
                border-gray-200
                ${open ? "border-b border-gray-300 rounded-t-2xl" : "rounded-2xl"}
            `}
        >
            <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                <div className="flex items-center gap-3">

                    {/* Icon */}
                    <div className={`transition-transform shrink-0 ${open ? "rotate-90" : ""}`}>
                        <GoChevronRight size={22} />
                    </div>

                    {/* Title */}
                    <div className="min-w-0">
                        <div className="font-semibold text-gray-800 word-break-words">
                            {capitalizeEachWord(workerCategory.name)}
                        </div>
                        <div className="text-sm text-gray-500">
                            {capitalizedFirst(workerCategory.description)}
                        </div>
                    </div>
                </div>

                <div className="flex justify-between sm:justify-end items-center gap-3 sm:gap-6">
                    {/* Badge */}
                    <Badge className="whitespace-nowrap">{workerCategory.total_items} item pekerjaan</Badge>

                    {/* Actions */}
                    <div className="flex gap-2">

                        {/* Edit */}
                        {can("ahsp.edit") && (
                            <Button
                                size="icon"
                                variant="edit"
                                onClick={(e) => {
                                    e.stopPropagation();
                                    onEdit?.(workerCategory);
                                }}
                            >
                                <LuPencil size={18} />
                            </Button>
                        )}

                        {/* Delete */}
                        {can("ahsp.delete") && (
                            <Button
                                size="icon"
                                variant="danger"
                                onClick={(e) => {
                                    e.stopPropagation();
                                    onDelete?.(workerCategory);
                                }}
                            >
                                {deletingId === workerCategory.id ? (
                                    <LuTrash2 size={18} className="animate-spin" />
                                ) : (
                                    <LuTrash2 size={18} />
                                )}
                            </Button>
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
};

export default WorkerCategoryHeaderCard;
