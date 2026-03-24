import { useRef, useEffect, useState } from "react";

interface AccordionProps {
    open: boolean;
    renderHeader: () => React.ReactNode;
    children: React.ReactNode;
}

export default function Accordion({
    open,
    renderHeader,
    children,
}: AccordionProps) {
    const contentRef = useRef<HTMLDivElement>(null);
    const [height, setHeight] = useState<number>(0);

    useEffect(() => {
        const el = contentRef.current;
        if (!el) return;

        if (!open) {
            setHeight(0);
            return;
        }

        setHeight(el.scrollHeight);

        const observer = new ResizeObserver(() => {
            setHeight(el.scrollHeight);
        });

        observer.observe(el);

        return () => observer.disconnect();
    }, [open]);

    return (
        <div className="border border-gray-300 rounded-2xl overflow-hidden">
            {renderHeader()}

            <div
                style={{
                    height,
                    transition: "height 100ms ease",
                }}
                className="overflow-hidden bg-white"
            >
                <div ref={contentRef} className="p-4">
                    {children}
                </div>
            </div>
        </div>
    );
}
