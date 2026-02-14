import React, { useEffect, useRef, useState } from "react";
import { Link } from "@inertiajs/react";

import { useSidebar } from "@/Context/SidebarContext";

import { FiSidebar } from "react-icons/fi";

import logoColor from "../../../assets/logo/logo-color.svg";
import logoWhite from "../../../assets/logo/logo-white.svg";

const AppHeader: React.FC = () => {
    const [isApplicationMenuOpen, setApplicationMenuOpen] = useState(false);

    const { isMobileOpen, toggleSidebar, toggleMobileSidebar } = useSidebar();

    const handleToggle = () => {
        if (typeof window !== "undefined" && window.innerWidth >= 1024) {
            toggleSidebar();
        } else {
            toggleMobileSidebar();
        }
    };

    const toggleApplicationMenu = () => {
        setApplicationMenuOpen((v) => !v);
    };

    const inputRef = useRef<HTMLInputElement | null>(null);

    useEffect(() => {
        const handleKeyDown = (event: KeyboardEvent) => {
            if ((event.metaKey || event.ctrlKey) && event.key.toLowerCase() === "k") {
                event.preventDefault();
                inputRef.current?.focus();
            }
        };

        if (typeof document !== "undefined") {
            document.addEventListener("keydown", handleKeyDown);
        }
        return () => {
            if (typeof document !== "undefined") {
                document.removeEventListener("keydown", handleKeyDown);
            }
        };
    }, []);

    return (
        <header className="sticky top-0 flex w-full bg-gray-50 border-gray-300 z-50 dark:border-gray-800 dark:bg-gray-900 lg:border-b">
            <div className="flex flex-col items-center justify-between grow lg:flex-row lg:px-6">

                {/* Left Section */}
                <div className="relative flex items-center w-full px-2 py-3 border-b border-gray-300 dark:border-gray-800 lg:border-b-0 lg:px-0 lg:py-4">

                    {/* Toggle Button */}
                    <button
                        className="flex items-center justify-center w-10 h-10 text-gray-500 border-gray-200 rounded-lg dark:border-gray-800 dark:text-gray-400 lg:h-11 lg:w-11 lg:border"
                        onClick={handleToggle}
                        aria-label="Toggle Sidebar"
                    >
                        {isMobileOpen ? (
                            <FiSidebar size={22} />
                        ) : (
                            <FiSidebar size={22} />
                        )}
                    </button>

                    {/* Centered Mobile Logo */}
                    <Link
                        href="/"
                        className="absolute left-1/2 -translate-x-1/2 lg:hidden"
                    >
                        <img
                            className="dark:hidden w-36"
                            src={logoColor}
                            alt="Logo"
                        />
                        <img
                            className="hidden dark:block w-36"
                            src={logoWhite}
                            alt="Logo"
                        />
                    </Link>
                </div>

                {/* Right Section */}
                <div
                    className={`${isApplicationMenuOpen ? "flex" : "hidden"}
            items-center justify-between w-full gap-4 px-5 py-4 lg:flex lg:justify-end lg:px-0`}
                >
                </div>
            </div>
        </header>

    );
};

export default AppHeader;
