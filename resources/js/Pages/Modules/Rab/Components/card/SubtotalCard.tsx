import React from "react";

interface SummaryCardProps {
    title: string;
    subtitle?: string;
    value: string | number;
    icon?: React.ReactNode;
};

const SubtotalCard = ({
    title,
    subtitle,
    value,
    icon,
}: SummaryCardProps) => {

    return (
        <div className="w-full rounded-2xl px-4 py-8 flex flex-col md:flex-row md:items-center justify-between gap-4
        bg-linear-to-r from-primary/95 to-primary text-white hover:shadow-md">

            {/* Left */}
            <div className="flex flex-col items-start">

                {/* Title */}
                <div className="flex items-center gap-3">
                    {icon && (
                        <div className="mt-1 text-white/80">
                            {icon}
                        </div>
                    )}

                    <h3 className="md:text-2xl font-semibold">
                        {title}
                    </h3>
                </div>

                {/* Subtitle */}
                <div>
                    {subtitle && (
                        <p className="text-sm text-white mt-1">
                            {subtitle}
                        </p>
                    )}
                </div>
            </div>

            {/* Right: Subtotal Value */}
            <div className="text-3xl md:text-3xl font-bold tracking-tight">
                {value}
            </div>
        </div>
    );
};

export default SubtotalCard;
