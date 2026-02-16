import React, {
    forwardRef,
    useId,
    useState,
    useEffect,
    useRef,
} from "react";

interface Option {
    value: string;
    label: string;
}

interface MultiSelectProps {
    options: Option[];
    value: string[];
    onChange: (selected: string[]) => void;

    label?: string;
    hint?: string;
    error?: boolean;
    success?: boolean;
    required?: boolean;
    optional?: boolean;

    placeholder?: string;
    disabled?: boolean;
    className?: string;
    id?: string;
}

const MultiSelect = forwardRef<HTMLButtonElement, MultiSelectProps>(
    (
        {
            options,
            value,
            onChange,
            label,
            hint,
            error = false,
            success = false,
            required = false,
            optional = false,
            placeholder = "Select option",
            disabled = false,
            className = "",
            id,
        },
        ref
    ) => {
        const generatedId = useId();
        const selectId = id ?? generatedId;
        const hintId = hint ? `${selectId}-hint` : undefined;

        const [open, setOpen] = useState(false);

        const [internalError, setInternalError] = useState<string | null>(null);

        const hasError = error || !!internalError;

        const wrapperRef = useRef<HTMLDivElement>(null);

        useEffect(() => {
            const handleClickOutside = (event: MouseEvent) => {
                if (
                    wrapperRef.current &&
                    !wrapperRef.current.contains(event.target as Node)
                ) {
                    setOpen(false);

                    if (required && value.length === 0) {
                        setInternalError("Minimal pilih 1 opsi");
                    }
                }
            };

            document.addEventListener("mousedown", handleClickOutside);

            return () => {
                document.removeEventListener("mousedown", handleClickOutside);
            };
        }, [required, value]);

        const toggleDropdown = () => {
            if (!disabled) setOpen((prev) => !prev);
        };

        const handleSelect = (optionValue: string) => {
            const newSelected = value.includes(optionValue)
                ? value.filter((v) => v !== optionValue)
                : [...value, optionValue];

            onChange(newSelected);

            if (newSelected.length > 0) {
                setInternalError(null);
            }
        };

        // Remove option badge
        const removeOption = (optionValue: string) => {
            const newSelected = value.filter((v) => v !== optionValue);

            onChange(newSelected);

            // Required check
            if (required && newSelected.length === 0) {
                setInternalError("Minimal pilih 1 opsi");
            }
        };

        const selectedOptions = options.filter((opt) =>
            value.includes(opt.value)
        );

        const stateClasses = disabled
            ? "bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed"
            : hasError
            ? "border-error-500 focus:ring-error-500/20"
            : success
            ? "border-success-500 focus:ring-success-500/20"
            : "border-gray-300 focus:ring-primary/20 focus:border-primary";

        return (
            <div
                ref={wrapperRef}
                className={`w-full space-y-1.5 ${className}`}
            >
                {/* Label */}
                {label && (
                    <label className="block text-sm font-medium text-gray-800">
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

                {/* Control */}
                <div className="relative">
                    <button
                        ref={ref}
                        type="button"
                        id={selectId}
                        disabled={disabled}
                        aria-invalid={hasError}
                        aria-describedby={hintId}
                        aria-expanded={open}
                        onClick={toggleDropdown}
                        className={`flex min-h-11 w-full flex-wrap items-center gap-2 rounded-lg border px-3 py-2 text-sm transition focus:outline-none focus:ring-1 ${stateClasses}`}
                    >
                        {selectedOptions.length > 0 ? (
                            selectedOptions.map((opt) => (
                                <span
                                    key={opt.value}
                                    className="flex items-center gap-1 rounded-full bg-gray-100 px-2 py-1 text-xs"
                                >
                                    {opt.label}

                                    {!disabled && (
                                        <button
                                            type="button"
                                            onClick={(e) => {
                                                e.stopPropagation();
                                                removeOption(opt.value);
                                            }}
                                            className="text-gray-500 hover:text-gray-700"
                                        >
                                            ×
                                        </button>
                                    )}
                                </span>
                            ))
                        ) : (
                            <span className="text-gray-400">
                                {placeholder}
                            </span>
                        )}
                    </button>

                    {/* Dropdown */}
                    {open && !disabled && (
                        <ul className="absolute z-30 mt-2 w-full max-h-60 overflow-auto rounded-lg border border-gray-200 bg-white shadow-lg">
                            {options.map((opt) => {
                                const isSelected = value.includes(opt.value);

                                return (
                                    <li
                                        key={opt.value}
                                        onClick={() => handleSelect(opt.value)}
                                        className={`cursor-pointer px-4 py-2 text-sm transition hover:bg-secondary hover:text-white ${
                                            isSelected
                                                ? "bg-primary/10 font-medium"
                                                : ""
                                        }`}
                                    >
                                        {opt.label}
                                    </li>
                                );
                            })}
                        </ul>
                    )}
                </div>

                {/* Hint / Error */}
                {(hint || error || internalError) && (
                    <p
                        id={hintId}
                        className={`text-xs ${
                            hasError
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

MultiSelect.displayName = "MultiSelect";
export default MultiSelect;
