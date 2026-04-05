import { RabDetailHeaderProps } from "@/types/rab";

import { formatCurrency } from "@/utils/formatCurrrency";
import { capitalizeEachWord } from "@/utils/capitalize";

import { GoChevronRight } from "react-icons/go";

const RabDetailCard = ({
    work_code,
    work_name,
    volume,
    unit,
    category,
    open,
    subtotal,
    toggle,
}: RabDetailHeaderProps) => {

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

                {/* Left */}
                <div className="flex items-center gap-3">

                    {/* Icon */}
                    <div className={`transition-transform shrink-0 ${open ? "rotate-90" : ""}`}>
                        <GoChevronRight size={22} />
                    </div>

                    {/* Title */}
                    <div className="min-w-0">
                        <div className="font-semibold text-gray-800 word-break-words">
                            <span className="font-normal text-blue-700">
                                {work_code}
                            </span>{" "}
                            {capitalizeEachWord(work_name)}
                        </div>
                        <div className="text-sm text-gray-500">
                            {capitalizeEachWord(category)} | {volume} {unit}
                        </div>
                    </div>
                </div>

                {/* Right */}
                <div className="flex justify-between sm:justify-end items-center gap-3 sm:gap-6">
                    {/* Subtotal */}
                    <div className="text-left sm:text-right">
                        <div className="text-sm text-gray-500">Subtotal</div>
                        <div className="font-semibold text-gray-800">
                            {formatCurrency(subtotal ?? 0)}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default RabDetailCard;
