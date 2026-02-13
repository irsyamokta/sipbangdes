import React from "react";
import { cn } from "../../lib/utils";
import ImageEmpty from "../../../assets/svg/image-empty.svg";

interface EmptyStateProps {
    title?: string;
    description?: string;
    imageSrc?: string;
    className?: string;
}

const EmptyState: React.FC<EmptyStateProps> = ({
    title = "Tidak ada data",
    description = "Kami tidak menemukan data untuk ditampilkan.",
    imageSrc = ImageEmpty,
    className = "",
}) => {
    return (
        <div className={cn("flex flex-col items-center justify-center text-center py-12", className)}>
            <img
                src={imageSrc}
                alt="Empty state"
                className="w-48 h-auto mb-6 opacity-80"
            />
            <h2 className="text-lg font-semibold text-gray-700 dark:text-gray-300">{title}</h2>
            <p className="text-sm text-gray-500 dark:text-gray-400 mt-2">{description}</p>
        </div>
    );
}

export default EmptyState;
