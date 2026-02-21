import React from "react";
import { router } from "@inertiajs/react";
import Button from "@/Components/ui/button/Button";

type BreadcrumbItem = {
    label: string;
    href?: string;
};

type HeaderTitleProps = {
    name?: string;
    title?: string;
    subtitle?: string;
    breadcrumb?: BreadcrumbItem[];

    actionLabel?: string;
    onActionClick?: () => void;
    actionIcon?: React.ReactNode;
};

const HeaderTitle: React.FC<HeaderTitleProps> = ({
    name,
    title,
    subtitle,
    breadcrumb,
    actionLabel,
    onActionClick,
    actionIcon,
}) => {
    const showAction = !!actionLabel && !!onActionClick;

    const hasHeading = !!name || !!title;
    const hasSubtitle = !!subtitle;
    const hasTitleSection = hasHeading || hasSubtitle;

    return (
        <div className="w-full">
            {/* Breadcrumb */}
            {breadcrumb && breadcrumb.length > 0 && (
                <div className="mb-3 text-sm sm:text-base text-gray-400 flex items-center gap-2 min-w-0">
                    {breadcrumb.map((item, index) => {
                        const isLast = index === breadcrumb.length - 1;

                        return (
                            <React.Fragment key={index}>
                                {item.href && !isLast ? (
                                    <span
                                        onClick={() => router.get(item.href as string)}
                                        className="cursor-pointer hover:text-primary whitespace-nowrap"
                                    >
                                        {item.label}
                                    </span>
                                ) : (
                                    <span
                                        className={`
                                            ${isLast ? "text-gray-600" : ""}
                                            ${
                                                isLast
                                                    ? "max-w-56 sm:max-w-none truncate sm:truncate-none"
                                                    : "whitespace-nowrap"
                                            }
                                        `}
                                        title={item.label}
                                    >
                                        {item.label}
                                    </span>
                                )}

                                {index < breadcrumb.length - 1 && (
                                    <span className="text-gray-400 whitespace-nowrap">
                                        ›
                                    </span>
                                )}
                            </React.Fragment>
                        );
                    })}
                </div>
            )}

            {/* Title Section */}
            {(hasTitleSection || showAction) && (
                <div
                    className={`
                        flex
                        ${
                            hasTitleSection
                                ? "flex-col gap-6 md:gap-0 md:flex-row md:justify-between md:items-center"
                                : "justify-end"
                        }
                    `}
                >
                    {/* Left Side (Title + Subtitle) */}
                    {hasTitleSection && (
                        <div>
                            {hasHeading && (
                                <h1 className="text-xl md:text-3xl font-semibold text-gray-900">
                                    {name
                                        ? `Selamat Datang, ${name}!`
                                        : title}
                                </h1>
                            )}

                            {hasSubtitle && (
                                <p className="mt-1 text-gray-500 text-xs md:text-base">
                                    {subtitle}
                                </p>
                            )}
                        </div>
                    )}

                    {/* Right Side (Action Button) */}
                    {showAction && (
                        <Button
                            startIcon={actionIcon}
                            onClick={onActionClick}
                            className="flex items-center gap-2 lg:py-6"
                        >
                            {actionLabel}
                        </Button>
                    )}
                </div>
            )}
        </div>
    );
};

export default HeaderTitle;
