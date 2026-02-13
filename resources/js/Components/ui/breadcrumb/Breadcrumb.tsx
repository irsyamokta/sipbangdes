import { Link } from "@inertiajs/react";

interface Crumb {
    label: string;
    href?: string;
}

interface BreadcrumbProps {
    crumbs: Crumb[];
}

const PageBreadcrumb: React.FC<BreadcrumbProps> = ({ crumbs }) => {
    const truncateLabel = (label: string) => {
        if (typeof window !== "undefined" && window.innerWidth <= 640) {
            return label.length > 10 ? label.slice(0, 10) + "..." : label;
        }
        return label;
    };

    return (
        <div className="flex flex-wrap items-center justify-between gap-3 mb-6">
            <nav>
                <ol className="flex items-center gap-1.5 overflow-hidden">
                    {crumbs.map((crumb, idx) => (
                        <li key={idx} className="flex items-center gap-1.5">
                            {crumb.href ? (
                                <Link
                                    href={crumb.href}
                                    className="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap"
                                >
                                    {truncateLabel(crumb.label)}
                                    {idx < crumbs.length - 1 && (
                                        <svg
                                            className="stroke-current"
                                            width="17"
                                            height="16"
                                            viewBox="0 0 17 16"
                                            fill="none"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                d="M6.0765 12.667L10.2432 8.50033L6.0765 4.33366"
                                                strokeWidth="1.2"
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                            />
                                        </svg>
                                    )}
                                </Link>
                            ) : (
                                <span className="text-sm text-gray-800 dark:text-white/90 whitespace-nowrap">
                                    {truncateLabel(crumb.label)}
                                </span>
                            )}
                        </li>
                    ))}
                </ol>
            </nav>
        </div>
    );
};

export default PageBreadcrumb;
