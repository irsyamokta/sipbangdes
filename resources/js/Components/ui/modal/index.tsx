import { useState, useRef, useEffect } from "react";

import Button from "@/Components/ui/button/Button";

import { AiOutlineLoading3Quarters, AiOutlineClose } from "react-icons/ai";

interface ModalProps {
    isOpen: boolean;
    onClose: () => void;
    className?: string;
    children: React.ReactNode;
    showCloseButton?: boolean;
    isFullscreen?: boolean;

    title?: string;
    subtitle?: string;

    formId?: string;
    submitLabel?: string;
    cancelLabel?: string;
    loading?: boolean;
    hideCancel?: boolean;
}

export const Modal: React.FC<ModalProps> = ({
    isOpen,
    onClose,
    children,
    className,
    showCloseButton = true,
    isFullscreen = false,
    title,
    subtitle,
    formId,
    submitLabel,
    cancelLabel,
    loading,
    hideCancel,
}) => {
    const modalRef = useRef<HTMLDivElement>(null);
    const [show, setShow] = useState(isOpen);
    const [animate, setAnimate] = useState(false);

    useEffect(() => {
        const handleEscape = (event: KeyboardEvent) => {
            if (event.key === "Escape") {
                onClose();
            }
        };

        if (isOpen) {
            document.addEventListener("keydown", handleEscape);
        }

        return () => {
            document.removeEventListener("keydown", handleEscape);
        };
    }, [isOpen, onClose]);

    useEffect(() => {
        let openTimer: NodeJS.Timeout;
        let closeTimer: NodeJS.Timeout;

        if (isOpen) {
            setShow(true);

            openTimer = setTimeout(() => {
                setAnimate(true);
            }, 10);

            document.body.style.overflow = "hidden";
        } else {
            setAnimate(false);

            closeTimer = setTimeout(() => {
                setShow(false);
            }, 200);

            document.body.style.overflow = "unset";
        }

        return () => {
            clearTimeout(openTimer);
            clearTimeout(closeTimer);
            document.body.style.overflow = "unset";
        };
    }, [isOpen]);

    if (!show) return null;

    const contentClasses = isFullscreen
        ? "w-full h-full"
        : "relative w-full rounded-xl bg-white  dark:bg-gray-900";

    return (
        <div className="fixed inset-0 flex items-center justify-center overflow-y-auto modal z-99999">
            {!isFullscreen && (
                <div
                    className={`fixed inset-0 bg-black/40 transition-opacity duration-200 ${animate ? "opacity-100" : "opacity-0"
                        }`}
                    onClick={onClose}
                />
            )}
            <div
                ref={modalRef}
                className={`
                    ${contentClasses} ${className}
                    flex flex-col max-h-[90vh]
                    transform transition-all duration-200 ease-out
                    ${animate
                        ? "opacity-100 scale-100 translate-y-0"
                        : "opacity-0 scale-95 translate-y-4"
                    }
                `}
                onClick={(e) => e.stopPropagation()}
            >
                {/* Header */}
                {(title || subtitle) && (
                    <div className="sticky top-0 z-10 bg-white dark:bg-gray-900 px-4 md:px-6 pt-6 border-gray-200 dark:border-gray-700 rounded-xl">
                        <h4 className="text-2xl font-semibold text-gray-800 dark:text-white/90">
                            {title}
                        </h4>

                        {subtitle && (
                            <p className="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                {subtitle}
                            </p>
                        )}
                    </div>
                )}

                {/* Close Button */}
                {showCloseButton && (
                    <button
                        onClick={onClose}
                        className="absolute right-3 top-3 z-20 flex h-9.5 w-9.5 items-center justify-center rounded-full bg-gray-100 text-gray-400 transition-colors hover:bg-gray-200 hover:text-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white sm:right-6 sm:top-6 sm:h-11 sm:w-11"
                    >
                        <AiOutlineClose className="h-5 w-5" />
                    </button>
                )}

                {/* Body (Scrollable Area) */}
                <div className="flex-1 overflow-y-auto no-scrollbar">
                    {children}
                </div>

                {/* Footer (Always Exists If Actions Provided) */}
                {(formId || submitLabel) && (
                    <div className="sticky bottom-0 z-10 bg-white dark:bg-gray-900 px-4 md:px-6 py-4 rounded-b-xl">
                        <div className="flex justify-end gap-3">
                            {!hideCancel && (
                                <Button
                                    variant="outline"
                                    onClick={onClose}
                                    disabled={loading}
                                >
                                    {cancelLabel ?? "Batal"}
                                </Button>
                            )}

                            {formId && (
                                <Button
                                    type="submit"
                                    form={formId}
                                    disabled={loading}
                                >
                                    {loading ? (
                                        <div className="flex items-center gap-2">
                                            <AiOutlineLoading3Quarters className="animate-spin text-lg" />
                                            Loading...
                                        </div>
                                    ) : (
                                        submitLabel ?? "Simpan"
                                    )}
                                </Button>
                            )}
                        </div>
                    </div>
                )}
            </div>
        </div>
    );
};
