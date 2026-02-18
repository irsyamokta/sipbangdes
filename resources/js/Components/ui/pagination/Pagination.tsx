import { router } from "@inertiajs/react";
import { LuChevronLeft, LuChevronRight } from "react-icons/lu";

interface PaginationProps {
    links: any[];
    filters: Record<string, any>;
    routeName: string;
}

export default function Pagination({ links, filters, routeName }: PaginationProps) {
    if (!links || links.length === 0) return null;

    const handleClick = (linkUrl?: string | null) => {
        if (!linkUrl) return;

        const url = new URL(linkUrl, window.location.origin);
        const page = url.searchParams.get("page") || "1";

        router.get(route(routeName), { ...filters, page }, { preserveState: true });
    };

    return (
        <div className="flex justify-center mt-6">
            <nav className="inline-flex items-center space-x-1">
                {links.map((link, index) => {
                    // Previous
                    if (index === 0) {
                        return (
                            <button
                                key={index}
                                disabled={!link.url}
                                onClick={() => handleClick(link.url)}
                                className={`flex items-center justify-center w-9 h-9 rounded-full transition ${link.url
                                    ? "text-gray-600 hover:bg-gray-100"
                                    : "text-gray-400 cursor-not-allowed"
                                }`}
                            >
                                <LuChevronLeft size={18} />
                            </button>
                        );
                    }

                    // Next
                    if (index === links.length - 1) {
                        return (
                            <button
                                key={index}
                                disabled={!link.url}
                                onClick={() => handleClick(link.url)}
                                className={`flex items-center justify-center w-9 h-9 rounded-full transition ${link.url
                                    ? "text-gray-600 hover:bg-gray-100"
                                    : "text-gray-400 cursor-not-allowed"
                                }`}
                            >
                                <LuChevronRight size={18} />
                            </button>
                        );
                    }

                    // Page numbers
                    return (
                        <button
                            key={index}
                            disabled={!link.url}
                            onClick={() => handleClick(link.url)}
                            className={`w-9 h-9 rounded-full text-sm font-medium transition ${link.active
                                ? "bg-primary text-white shadow"
                                : "text-gray-700 hover:bg-gray-100"
                            }`}
                            dangerouslySetInnerHTML={{ __html: link.label }}
                        />
                    );
                })}
            </nav>
        </div>
    );
}
