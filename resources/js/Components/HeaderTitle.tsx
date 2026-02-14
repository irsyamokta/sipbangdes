import React from "react";

type HeaderTitleProps = {
    name?: string;
    title?: string;
    subtitle?: string;
};

const HeaderTitle: React.FC<HeaderTitleProps> = ({
    name,
    title,
    subtitle = "Kelola sistem dan pantau seluruh aktivitas",
}) => {
    return (
        <div className="w-full rounded-xl">
            <h1 className="text-xl md:text-3xl font-semibold text-gray-900">
                {name ? `Selamat Datang, ${name}!` : title}
            </h1>

            <p className="mt-1 text-gray-500 text-xs md:text-base">
                {subtitle}
            </p>
        </div>
    );
};

export default HeaderTitle;
