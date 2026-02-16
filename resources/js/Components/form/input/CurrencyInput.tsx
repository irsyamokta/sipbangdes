import React, { forwardRef, useId, useState } from "react";

type NativeInputProps =
    React.ComponentPropsWithoutRef<"input">;

interface CurrencyInputProps
    extends Omit<NativeInputProps, "value" | "onChange"> {
    value: number | null;
    onChange: (value: number | null) => void;
    label?: string;
    hint?: string;
    error?: boolean;
    success?: boolean;
    required?: boolean;
    optional?: boolean;
    isCurrency?: boolean;
}

const CurrencyInput = forwardRef<HTMLInputElement, CurrencyInputProps>(
    (
        {
            value,
            onChange,
            label,
            hint,
            error = false,
            success = false,
            required = false,
            optional = false,
            isCurrency = false,
            id,
            disabled,
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

        const formatNumber = (num: number | null) => {
            if (num === null || isNaN(num)) return "";
            return new Intl.NumberFormat("id-ID").format(num);
        };

        const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
            const rawValue = e.target.value.replace(/\./g, "");

            if (!/^\d*$/.test(rawValue)) return;

            if (internalError) {
                setInternalError(null);
            }

            if (rawValue === "") {
                onChange(null);
            } else {
                onChange(Number(rawValue));
            }
        };

        const baseClasses =
            `h-11 w-full rounded-lg border px-4 ${isCurrency ? "pl-9" : ""
            } text-sm shadow-theme-xs transition-all duration-200 placeholder:text-gray-400 focus:outline-none focus:ring-1 bg-transparent`;

        const stateClasses = disabled
            ? "cursor-not-allowed opacity-60 border-gray-300 bg-gray-100"
            : hasError
                ? "border-error-500 focus:border-error-300 focus:ring-error-500/20"
                : success
                    ? "border-success-500 focus:border-success-300 focus:ring-success-500/20"
                    : "border-gray-300 focus:border-primary focus:ring-primary/20";

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

                {/* Input */}
                <div className="relative">
                    {isCurrency && (
                        <span className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            Rp
                        </span>
                    )}

                    <input
                        ref={ref}
                        id={inputId}
                        type="text"
                        inputMode="numeric"
                        pattern="[\d.]*"
                        disabled={disabled}
                        aria-invalid={hasError}
                        aria-describedby={hintId}
                        value={formatNumber(value)}
                        onChange={handleChange}
                        className={`${baseClasses} ${stateClasses} ${className}`}
                        {...props}
                    />
                </div>

                {/* Hint / Error */}
                {(hint || error || hasError) && (
                    <p
                        id={hintId}
                        className={`text-xs ${hasError
                                ? "text-error-500"
                                : success
                                    ? "text-success-500"
                                    : "text-gray-500"
                            }`}
                    >
                        {internalError ?? error ?? hint}
                    </p>
                )}
            </div>
        );
    }
);

CurrencyInput.displayName = "CurrencyInput";
export default CurrencyInput;
