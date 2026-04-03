import { ReactNode, useRef, useEffect, useState } from "react";
import AhspHeaderCard from "../card/AhspHeaderCard";
import { AhspHeaderCardProps } from "@/types/ahsp";

interface AhspAccordionProps extends AhspHeaderCardProps {
    children: ReactNode;
}

const AhspAccordion = ({
    children,
    ...headerProps
}: AhspAccordionProps) => {
    const contentRef = useRef<HTMLDivElement>(null);
    const [height, setHeight] = useState<number>(0);

    useEffect(() => {
        const el = contentRef.current;
        if (!el) return;

        if (!headerProps.open) {
            setHeight(0);
            return;
        }

        setHeight(el.scrollHeight);

        const observer = new ResizeObserver(() => {
            setHeight(el.scrollHeight);
        });

        observer.observe(el);

        return () => observer.disconnect();
    }, [headerProps.open]);

    return (
        <div className="border border-gray-300 rounded-2xl overflow-hidden">
            {/* Header */}
            <AhspHeaderCard {...headerProps} />

            {/* Content */}
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
};

export default AhspAccordion;
