import React, { forwardRef, useId } from "react";

type NativeCheckboxProps = React.ComponentPropsWithoutRef<"input">;

interface CheckboxProps
    extends Omit<NativeCheckboxProps, "type" | "onChange" | "checked"> {
    label?: string;
    hint?: string;
    error?: boolean;
    success?: boolean;
    required?: boolean;
    optional?: boolean;
    checked: boolean;
    onChange: (checked: boolean) => void;
}

const Checkbox = forwardRef<HTMLInputElement, CheckboxProps>(
    (
        {
            label,
            hint,
            error = false,
            success = false,
            required = false,
            optional = false,
            checked,
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

        const stateClasses = disabled
            ? "cursor-not-allowed opacity-60 border-gray-300 bg-gray-100"
            : error
                ? "border-error-500"
                : success
                    ? "border-success-500"
                    : "border-gray-300";

        return (
            <div className="w-full space-y-1.5">
                <div
                    className={`flex items-start space-x-3 ${disabled ? "cursor-not-allowed opacity-60" : "cursor-pointer"
                        }`}
                >
                    {/* Checkbox */}
                    <div className="relative w-5 h-5 mt-0.5">
                        <input
                            ref={ref}
                            id={inputId}
                            type="checkbox"
                            checked={checked}
                            disabled={disabled}
                            aria-invalid={error}
                            aria-describedby={hintId}
                            onChange={(e) => onChange(e.target.checked)}
                            className={`w-5 h-5 appearance-none rounded-md border transition-colors
                checked:bg-primary checked:border-transparent
                ${stateClasses} ${className}`}
                            {...props}
                        />

                        {/* Check Icon */}
                        {checked && (
                            <svg
                                className="absolute pointer-events-none top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"
                                width="14"
                                height="14"
                                viewBox="0 0 14 14"
                                fill="none"
                            >
                                <path
                                    d="M11.6666 3.5L5.24992 9.91667L2.33325 7"
                                    stroke="white"
                                    strokeWidth="2"
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                />
                            </svg>
                        )}
                    </div>

                    {/* Label */}
                    {label && (
                        <label
                            htmlFor={inputId}
                            className="text-sm font-medium text-gray-800 select-none"
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
                </div>

                {/* Hint */}
                {hint && (
                    <p
                        id={hintId}
                        className={`text-xs ml-8 ${error
                                ? "text-error-500"
                                : success
                                    ? "text-success-500"
                                    : "text-gray-500"
                            }`}
                    >
                        {hint}
                    </p>
                )}
            </div>
        );
    }
);

Checkbox.displayName = "Checkbox";

export default Checkbox;
