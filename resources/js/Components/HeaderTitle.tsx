import React from "react";
import Button from "@/Components/ui/button/Button";

type HeaderTitleProps = {
    name?: string;
    title?: string;
    subtitle?: string;

    actionLabel?: string;
    onActionClick?: () => void;
    actionIcon?: React.ReactNode;
};

const HeaderTitle: React.FC<HeaderTitleProps> = ({
    name,
    title,
    subtitle = "Kelola sistem dan pantau seluruh aktivitas",
    actionLabel,
    onActionClick,
    actionIcon,
}) => {
    const showAction = actionLabel && onActionClick;

    return (
        <div className="flex flex-col gap-6 md:gap-0 md:flex-row md:justify-between md:items-center w-full">
            <div>
                <h1 className="text-xl md:text-3xl font-semibold text-gray-900">
                    {name ? `Selamat Datang, ${name}!` : title}
                </h1>

                <p className="mt-1 text-gray-500 text-xs md:text-base">
                    {subtitle}
                </p>
            </div>

            {showAction && (
                <Button startIcon={actionIcon} onClick={onActionClick} className="flex items-center gap-2 lg:py-6">
                    {actionLabel}
                </Button>
            )}
        </div>
    );
};

export default HeaderTitle;
