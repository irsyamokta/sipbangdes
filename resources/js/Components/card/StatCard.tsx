import React from "react";
import CountUp from "react-countup";

type Variant = "default" | "dashboard";

type StatCardProps = {
    title: string;
    value: string | number;
    subtitle?: string;
    icon: React.ReactNode;
    iconBgColor?: string;
    iconColor?: string;
    variant?: Variant;
};

const StatCard: React.FC<StatCardProps> = ({
    title,
    value,
    subtitle,
    icon,
    iconBgColor = "bg-gray-100",
    iconColor = "text-gray-600",
    variant = "default",
}) => {
    const styles = {
        default: {
            title: "text-gray-500 text-sm font-medium",
            value: "text-xl font-bold text-black",
            subtitle: "text-gray-500 text-sm",
        },
        dashboard: {
            title: "text-gray-800 text-xl font-semibold",
            value: "text-4xl font-bold text-black",
            subtitle: "text-gray-500 text-sm",
        },
    };

    const current = styles[variant];

    return (
        <div className="w-full flex items-center justify-between rounded-2xl border border-gray-300 px-4 py-6 bg-white hover:shadow-md transition">
            <div className="w-full">
                {/* Header */}
                <div className="flex justify-between items-center">
                    <h3 className={current.title}>{title}</h3>

                    <div
                        className={`flex items-center justify-center w-10 h-10 rounded-lg ${iconBgColor}`}
                    >
                        <div className={`${iconColor} text-lg`}>
                            {icon}
                        </div>
                    </div>
                </div>

                {/* Value */}
                <div className={`mt-2 ${current.value}`}>
                    {typeof value === "number" ? (
                        <CountUp
                            end={value}
                            duration={1.5}
                            separator=","
                        />
                    ) : (
                        value
                    )}
                </div>

                {/* Subtitle */}
                {subtitle && (
                    <p className={`mt-2 ${current.subtitle}`}>
                        {subtitle}
                    </p>
                )}
            </div>
        </div>
    );
};

export default StatCard;
