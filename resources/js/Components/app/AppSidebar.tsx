import { Link, usePage } from "@inertiajs/react";
import { useCallback } from "react";

import Button from "@/Components/ui/button/Button";
import { useSidebar } from "@/Context/SidebarContext";

import { HiDotsHorizontal } from "react-icons/hi";
import { MdLogout } from "react-icons/md";

import logoWhite from "../../../assets/logo/logo-white.svg";
import logoMini from "../../../assets/logo/logo-mini-white.svg";

type NavItem = {
    name: string;
    icon: React.ReactNode;
    path: string;
};

type SidebarSection = {
    title: string;
    items: NavItem[];
};

type AppSidebarProps = {
    sections: SidebarSection[];
};

const AppSidebar: React.FC<AppSidebarProps> = ({ sections }) => {
    const { isExpanded, isMobileOpen, isHovered, setIsHovered } = useSidebar();
    const { url, props }: any = usePage();
    const user = props?.auth?.user;

    const isOpen = isExpanded || isHovered || isMobileOpen;

    const isActive = useCallback(
        (path?: string) => {
            if (!path) return false;
            const cleanUrl = url.split("?")[0];
            return (
                cleanUrl === path || cleanUrl.startsWith(path + "/")
            );
        },
        [url]
    );

    return (
        <aside
            className={`fixed top-0 left-0 h-screen bg-primary text-white flex flex-col
            transition-[width] duration-500
            overflow-x-hidden
            z-50
            w-60
            md:w-70
            lg:w-20
            ${isOpen ? "lg:w-70" : ""}
            ${isMobileOpen ? "translate-x-0 transition-all duration-500" : "-translate-x-full transition-all duration-500"}
            lg:translate-x-0`}
            onMouseEnter={() => !isExpanded && setIsHovered(true)}
            onMouseLeave={() => setIsHovered(false)}
        >
            {/* Logo */}
            <div className="px-4 py-6 sm:border-b border-white/10">
                <Link href="/" className="flex lg:justify-start">
                    <img
                        src={isOpen ? logoWhite : logoMini}
                        alt="Logo"
                        className={`${isOpen ? "w-50" : "w-10"} -mt-2`}
                    />
                </Link>
            </div>

            {/* Menu */}
            <div className="flex-1 no-scrollbar overflow-x-hidden min-w-0 px-4 py-6 space-y-8">
                {sections.map((section) => (
                    <div key={section.title}>
                        {/* Section Title */}
                        <div className="mb-3">
                            {isOpen ? (
                                <p className="text-xs font-semibold text-white/50 uppercase tracking-wider whitespace-nowrap">
                                    {section.title}
                                </p>
                            ) : (
                                <div className="flex">
                                    <HiDotsHorizontal className="text-white/70" />
                                </div>
                            )}
                        </div>

                        {/* Items */}
                        <ul className="space-y-1">
                            {section.items.map((item) => (
                                <li key={item.name}>
                                    <Link
                                        href={item.path}
                                        className={`flex items-center gap-3 px-3 py-2 rounded-lg
                                        transition-colors duration-200
                                        ${isActive(item.path)
                                                ? "bg-alternate"
                                                : "hover:bg-alternate"
                                            }`}
                                    >
                                        <span
                                            className={`text-lg shrink-0 ${isActive(item.path)
                                                ? "text-white"
                                                : "text-white/80"
                                                }`}
                                        >
                                            {item.icon}
                                        </span>

                                        {isOpen && (
                                            <span className="text-sm font-medium whitespace-nowrap">
                                                {item.name}
                                            </span>
                                        )}
                                    </Link>
                                </li>
                            ))}
                        </ul>
                    </div>
                ))}
            </div>

            {/* User Profile */}
            <div className="border-t border-white/10 p-4">
                <div className="flex items-center gap-3 min-w-0">
                    <div className="w-10 h-10 rounded-full bg-blue-300/60 flex items-center justify-center text-sm font-semibold shrink-0">
                        {user?.name?.slice(0, 2).toUpperCase()}
                    </div>

                    {isOpen && (
                        <>
                            <div className="flex-1 min-w-0">
                                <p className="text-sm font-medium truncate">
                                    {user?.name}
                                </p>
                                <p className="text-xs text-white/60 capitalize truncate">
                                    {user?.role}
                                </p>
                            </div>

                            <Link
                                href={route("logout")}
                                method="post"
                                as="button"
                                className="shrink-0"
                            >
                                <Button size="icon" className="hover:bg-white/25">
                                    <MdLogout size={18} />
                                </Button>
                            </Link>
                        </>
                    )}
                </div>
            </div>
        </aside>
    );
};

export default AppSidebar;
