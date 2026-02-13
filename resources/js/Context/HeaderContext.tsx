import { createContext, useContext, useEffect, useState, ReactNode } from "react";

type HeaderContextType = {
    isScrolled: boolean;
    isMobile: boolean;
    menuOpen: boolean;
    toggleMenu: () => void;
    setMenuOpen: (val: boolean) => void;
};

const HeaderContext = createContext<HeaderContextType | undefined>(undefined);

export const useHeader = () => {
    const context = useContext(HeaderContext);
    if (!context) throw new Error("useHeader must be used within HeaderProvider");
    return context;
};

export const HeaderProvider = ({ children }: { children: ReactNode }) => {
    const [menuOpen, setMenuOpen] = useState(false);
    const [isScrolled, setIsScrolled] = useState(false);
    const [isMobile, setIsMobile] = useState(window.innerWidth < 768);

    useEffect(() => {
        const handleScroll = () => setIsScrolled(window.scrollY > 0);
        const handleResize = () => setIsMobile(window.innerWidth < 768);

        window.addEventListener("scroll", handleScroll);
        window.addEventListener("resize", handleResize);

        return () => {
            window.removeEventListener("scroll", handleScroll);
            window.removeEventListener("resize", handleResize);
        };
    }, []);

    const toggleMenu = () => setMenuOpen((prev) => !prev);

    return (
        <HeaderContext.Provider
            value={{ isScrolled, isMobile, menuOpen, toggleMenu, setMenuOpen }}
        >
            {children}
        </HeaderContext.Provider>
    );
};
