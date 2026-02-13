import React, { forwardRef, useId } from "react";

type NativeRadioProps = React.ComponentPropsWithoutRef<"input">;

interface RadioProps
    extends Omit<NativeRadioProps, "type" | "onChange" | "checked"> {
    label?: string;
    hint?: string;
    error?: boolean;
    success?: boolean;
    required?: boolean;
    optional?: boolean;

    name: string;
    value: string;
    checked: boolean;
    onChange: (value: string) => void;

    disabled?: boolean;
    className?: string;
}

const Radio = forwardRef<HTMLInputElement, RadioProps>(
    (
        {
            label,
            hint,
            error = false,
            success = false,
            required = false,
            optional = false,
            name,
            value,
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
            ? "border-gray-200 bg-gray-100"
            : error
                ? "border-error-500"
                : success
                    ? "border-success-500"
                    : "border-gray-300";

        return (
            <div className="space-y-1.5">
                <label
                    htmlFor={inputId}
                    className={`relative flex items-center gap-3 text-sm font-medium select-none ${disabled
                            ? "text-gray-300 cursor-not-allowed"
                            : "text-gray-700 cursor-pointer"
                        } ${className}`}
                >
                    <input
                        ref={ref}
                        id={inputId}
                        name={name}
                        type="radio"
                        value={value}
                        checked={checked}
                        disabled={disabled}
                        aria-invalid={error}
                        aria-describedby={hintId}
                        onChange={() => onChange(value)}
                        className="sr-only"
                        {...props}
                    />

                    {/* Outer circle */}
                    <span
                        className={`flex h-5 w-5 items-center justify-center rounded-full border-[1.5px] transition-colors ${stateClasses} ${checked && !disabled
                                ? "border-brand-500"
                                : ""
                            }`}
                    >
                        {/* Inner dot */}
                        {checked && (
                            <span className="h-2.5 w-2.5 rounded-full bg-primary" />
                        )}
                    </span>

                    {/* Label text */}
                    {label && (
                        <span>
                            {label}

                            {required && (
                                <span className="ml-1 text-error-500">*</span>
                            )}

                            {!required && optional && (
                                <span className="ml-2 text-xs text-gray-400">
                                    (Optional)
                                </span>
                            )}
                        </span>
                    )}
                </label>

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

Radio.displayName = "Radio";

export default Radio;
