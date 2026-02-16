import { useRef, useEffect } from "react";

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
        if (isOpen) {
            document.body.style.overflow = "hidden";
        } else {
            document.body.style.overflow = "unset";
        }

        return () => {
            document.body.style.overflow = "unset";
        };
    }, [isOpen]);

    if (!isOpen) return null;

    const contentClasses = isFullscreen
        ? "w-full h-full"
        : "relative w-full rounded-xl bg-white  dark:bg-gray-900";

    return (
        <div className="fixed inset-0 flex items-center justify-center overflow-y-auto modal z-99999">
            {!isFullscreen && (
                <div
                    className="fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[32px]"
                    onClick={onClose}
                ></div>
            )}
            <div
                ref={modalRef}
                className={`${contentClasses} ${className} flex flex-col max-h-[90vh]`}
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
                    <div className="sticky bottom-0 z-10 bg-white dark:bg-gray-900 px-4 md:px-6 py-4 border-t border-gray-200 dark:border-gray-700">
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
