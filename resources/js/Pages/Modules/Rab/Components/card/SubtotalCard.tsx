import React from "react";

interface SummaryCardProps {
    title: string;
    subtitle?: string;
    value: string | number;
    icon?: React.ReactNode;
};

const SubtotalCard: React.FC<SummaryCardProps> = ({
    title,
    subtitle,
    value,
    icon,
}) => {
    return (
        <div className="w-full rounded-2xl px-4 py-8 flex flex-col md:flex-row md:items-center justify-between gap-4
        bg-linear-to-r from-primary/95 to-primary text-white hover:shadow-md">

            {/* Left */}
            <div className="flex flex-col items-start">
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
                <div>
                    {subtitle && (
                        <p className="text-sm text-white mt-1">
                            {subtitle}
                        </p>
                    )}
                </div>
            </div>

            {/* Right */}
            <div className="text-3xl md:text-3xl font-bold tracking-tight">
                {value}
            </div>
        </div>
    );
};

export default SubtotalCard;
