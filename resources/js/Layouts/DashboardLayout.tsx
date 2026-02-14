import { PropsWithChildren } from "react";
import { usePage } from "@inertiajs/react";

import Backdrop from "@/Layouts/Backdrop";
import { SidebarProvider, useSidebar } from "@/Context/SidebarContext";

import AppHeader from "@/Components/app/AppHeader";
import AppSidebar from "@/Components/app/AppSidebar";

import { getSidebarConfig } from "@/config/sidebar.config";

function LayoutContent({ children }: PropsWithChildren) {
    const { auth }: any = usePage().props;
    const role = auth?.user?.role || "planner";

    const navItems = getSidebarConfig(role);
    const { isExpanded, isHovered, isMobileOpen } = useSidebar();

    return (
        <div className="min-h-screen xl:flex">
            <div>
                <AppSidebar sections={navItems} />
                <Backdrop />
            </div>
            <div
                className={`flex-1 transition-all duration-500 ease-in-out ${isExpanded || isHovered ? "lg:ml-70" : "lg:ml-20"
                    } ${isMobileOpen ? "ml-0" : ""}`}
            >
                <AppHeader />
                <div className="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
                    {children}
                </div>
            </div>
        </div>
    );
};

const DashboardLayout: React.FC<PropsWithChildren> = ({ children }) => {
    return (
        <SidebarProvider>
            <LayoutContent>{children}</LayoutContent>
        </SidebarProvider>
    );
};

export default DashboardLayout;
