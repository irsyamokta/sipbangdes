import { ReactNode } from "react";
import { capitalizeEachWord } from "@/utils/capitalize";

interface ProgressCardProps {
    title: string;
    description?: string;
    label?: string;
    value: number;
    icon?: ReactNode;
    progressColor?: string;
}

const ProgressCard = ({
    title,
    description,
    label = "Progres",
    value,
    icon,
    progressColor = "bg-primary"
}: ProgressCardProps) => {

    return (
        <div className="rounded-2xl border border-gray-200 bg-white p-6 transition hover:shadow-md">

            {/* Header */}
            <h1 className="flex items-center gap-2 text-xl md:text-2xl font-semibold">

                {icon && (
                    <span className="text-lg">
                        {icon}
                    </span>
                )}

                {capitalizeEachWord(title)}

            </h1>

            {/* Description */}
            {description && (
                <p className="text-sm sm:text-base text-gray-500 mt-1">
                    {description}
                </p>
            )}

            {/* Progress Bar */}
            <div className="mt-6">
                <div className="flex justify-between text-sm mb-1">
                    <span className="text-gray-500">
                        {label}
                    </span>

                    <span>
                        {value}%
                    </span>
                </div>

                <div className="w-full bg-gray-200 rounded-full h-2 md:h-2.5">
                    <div
                        className={`${progressColor} h-2 md:h-2.5 rounded-full transition-all`}
                        style={{
                            width: `${Math.min(value, 100)}%`
                        }}
                    />
                </div>
            </div>
        </div>
    );
};

export default ProgressCard;