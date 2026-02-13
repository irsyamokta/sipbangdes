import React, { forwardRef, useId, useState } from "react";

type NativeTextareaProps =
    React.ComponentPropsWithoutRef<"textarea">;

interface TextAreaProps
    extends Omit<NativeTextareaProps, "onChange"> {
    label?: string;
    hint?: string;
    error?: boolean;
    success?: boolean;
    required?: boolean;
    optional?: boolean;
    maxLength?: number;
    value?: string;
    onChange?: (value: string) => void;
}

const TextArea = forwardRef<HTMLTextAreaElement, TextAreaProps>(
    (
        {
            label,
            hint,
            error = false,
            success = false,
            required = false,
            optional = false,
            maxLength = 255,
            rows = 3,
            value = "",
            onChange,
            id,
            disabled = false,
            className = "",
            ...props
        },
        ref
    ) => {
        const generatedId = useId();
        const inputId = id ?? generatedId;
        const hintId = hint ? `${inputId}-hint` : undefined;

        const [internalError, setInternalError] = useState<string | null>(null);

        const hasError = error || !!internalError;

        const handleChange = (e: React.ChangeEvent<HTMLTextAreaElement>) => {
            const newValue = e.target.value.slice(0, maxLength);

            if (internalError && newValue.trim() !== "") {
                setInternalError(null);
            }

            onChange?.(newValue);
        };

        const handleBlur = () => {
            if (required && value.trim() === "") {
                setInternalError("Field ini wajib diisi");
            }
        };

        const stateClasses = disabled
            ? "bg-gray-100 text-gray-500 border-gray-300 cursor-not-allowed opacity-60"
            : hasError
            ? "border-error-500 focus:ring-error-500/20 focus:border-error-500"
            : success
            ? "border-success-500 focus:ring-success-500/20 focus:border-success-500"
            : "border-gray-300 focus:border-primary focus:ring-primary/20";

        return (
            <div className="w-full space-y-1.5">
                {/* Label */}
                {label && (
                    <label
                        htmlFor={inputId}
                        className="block text-sm font-medium text-gray-800"
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

                {/* Textarea */}
                <textarea
                    ref={ref}
                    id={inputId}
                    rows={rows}
                    value={value}
                    required={required}
                    disabled={disabled}
                    aria-invalid={hasError}
                    aria-describedby={hintId}
                    onChange={handleChange}
                    onBlur={handleBlur}
                    className={`w-full rounded-lg border px-4 py-2.5 text-sm shadow-theme-xs
                    transition focus:outline-none focus:ring-1
                    bg-transparent ${stateClasses} ${className}`}
                    {...props}
                />

                {/* Hint / Error + Counter */}
                <div className="flex justify-between text-xs -mt-1">
                    {/* Hint atau Internal Error */}
                    {(hint || internalError) && (
                        <p
                            id={hintId}
                            className={`${
                                hasError
                                    ? "text-error-500"
                                    : success
                                    ? "text-success-500"
                                    : "text-gray-500"
                            }`}
                        >
                            {internalError ?? hint}
                        </p>
                    )}

                    {/* Counter */}
                    {maxLength && (
                        <p
                            className={`ml-auto ${
                                value.length >= maxLength
                                    ? "text-error-500 font-medium"
                                    : "text-gray-500"
                            }`}
                        >
                            {value.length}/{maxLength}
                        </p>
                    )}
                </div>
            </div>
        );
    }
);

TextArea.displayName = "TextArea";
export default TextArea;
