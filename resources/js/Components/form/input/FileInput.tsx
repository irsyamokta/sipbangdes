import React, { forwardRef, useId, useState } from "react";
import { LuUpload } from "react-icons/lu";

type NativeFileInputProps =
    React.ComponentPropsWithoutRef<"input">;

interface FileInputProps extends NativeFileInputProps {
    label?: string;
    hint?: string;
    error?: boolean;
    success?: boolean;
    required?: boolean;
    optional?: boolean;
    variant?: "default" | "card";
}

const FileInput = forwardRef<HTMLInputElement, FileInputProps>(
    (
        {
            label,
            hint,
            error = false,
            success = false,
            optional = false,
            required = false,
            variant = "default",
            className = "",
            id,
            disabled,
            multiple,
            onChange,
            ...props
        },
        ref
    ) => {
        const generatedId = useId();
        const inputId = id ?? generatedId;
        const hintId = hint ? `${inputId}-hint` : undefined;

        const [files, setFiles] = useState<FileList | null>(null);

        const [internalError, setInternalError] = useState<string | null>(null);

        const hasError = error || !!internalError;

        const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
            const selectedFiles = e.target.files;

            setFiles(selectedFiles);

            if (selectedFiles && selectedFiles.length > 0) {
                setInternalError(null);
            }

            onChange?.(e);
        };

        const handleBlur = () => {
            if (required && (!files || files.length === 0)) {
                setInternalError("File wajib diunggah");
            }
        };

        const baseClasses =
            "h-11 w-full overflow-hidden rounded-lg border text-sm shadow-theme-xs transition-colors file:mr-5 file:cursor-pointer file:rounded-l-lg file:border-0 file:border-r file:border-gray-200 file:bg-gray-50 file:py-3 file:pl-3.5 file:pr-3 file:text-sm file:text-gray-700 hover:file:bg-gray-100 focus:outline-none dark:file:border-gray-800 dark:file:bg-white/5 dark:file:text-gray-400";

        const stateClasses = disabled
            ? "cursor-not-allowed opacity-60 border-gray-300 bg-gray-100 dark:bg-gray-800"
            : hasError
                ? "border-error-500 focus:border-error-300"
                : success
                    ? "border-success-500 focus:border-success-300"
                    : "border-gray-300 focus:border-primary dark:border-gray-700";

        const stateBorder = disabled
            ? "border-gray-300 bg-gray-100 opacity-60 cursor-not-allowed"
            : hasError
                ? "border-error-500"
                : success
                    ? "border-success-500"
                    : "border-gray-300 hover:border-primary";

        const renderFileText = () => {
            if (!files || files.length === 0)
                return "Klik untuk unggah file";

            if (files.length === 1) return files[0].name;

            return `${files.length} file dipilih`;
        };

        return (
            <div className="w-full space-y-1.5">
                {/* Label */}
                {label && (
                    <label
                        htmlFor={inputId}
                        className="block text-sm font-medium text-gray-700"
                    >
                        {label}
                        {required && (
                            <span className="ml-1 text-error-500">*</span>
                        )}
                        {!required && optional && (
                            <span className="ml-2 text-xs text-gray-400">
                                (Optional)
                            </span>
                        )}
                    </label>
                )}

                {/* Card Variant */}
                {variant === "card" && (
                    <label
                        htmlFor={inputId}
                        className={`flex h-40 w-full cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed bg-gray-50 text-gray-500 transition ${stateBorder}`}
                    >
                        <LuUpload size={28} className="mb-2" />

                        <span className="text-sm text-center px-4">
                            {renderFileText()}
                        </span>

                        <input
                            ref={ref}
                            id={inputId}
                            type="file"
                            disabled={disabled}
                            multiple={multiple}
                            onChange={handleChange}
                            onBlur={handleBlur}
                            className="hidden"
                            {...props}
                        />
                    </label>
                )}

                {/* Default Variant */}
                {variant === "default" && (
                    <input
                        ref={ref}
                        id={inputId}
                        type="file"
                        disabled={disabled}
                        multiple={multiple}
                        onChange={handleChange}
                        onBlur={handleBlur}
                        className={`${baseClasses} ${stateClasses} ${className}`}
                        {...props}
                    />
                )}

                {/* Hint / Error */}
                {(hint || hasError) && (
                    <p
                        id={hintId}
                        className={`text-xs ${hasError
                                ? "text-error-500"
                                : success
                                    ? "text-success-500"
                                    : "text-gray-500"
                            }`}
                    >
                        {internalError ?? hint}
                    </p>
                )}
            </div>
        );
    }
);

FileInput.displayName = "FileInput";
export default FileInput;
