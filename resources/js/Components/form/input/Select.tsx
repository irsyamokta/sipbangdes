import React, {
    forwardRef,
    useId,
    useState,
    useEffect,
    useRef,
} from "react";
import { LuChevronDown } from "react-icons/lu";

interface Option {
    value: string | boolean | number;
    label: string;
}

interface SelectProps {
    options: Option[];
    value: string | boolean | number;
    onChange: (value: string | boolean | number) => void;

    label?: string;
    hint?: string;
    error?: string | boolean;
    success?: boolean;
    required?: boolean;
    optional?: boolean;

    placeholder?: string;
    disabled?: boolean;
    hideChevron?: boolean;
    className?: string;
    id?: string;
}

const Select = forwardRef<HTMLButtonElement, SelectProps>(
    (
        {
            options,
            value,
            onChange,
            label,
            hint,
            error = false || "",
            success = false,
            required = false,
            optional = false,
            placeholder = "Pilih Opsi",
            disabled = false,
            hideChevron = false,
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

        const selected = options.find((opt) => opt.value === value);

        useEffect(() => {
            const handleClickOutside = (event: MouseEvent) => {
                if (
                    wrapperRef.current &&
                    !wrapperRef.current.contains(event.target as Node)
                ) {
                    setOpen(false);

                    // Required validation saat dropdown ditutup
                    if (required && !value) {
                        setInternalError(null);
                    }
                }
            };

            document.addEventListener("mousedown", handleClickOutside);
            return () => {
                document.removeEventListener("mousedown", handleClickOutside);
            };
        }, [required, value]);

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

                {/* Trigger */}
                <div className="relative">
                    <button
                        ref={ref}
                        id={selectId}
                        type="button"
                        disabled={disabled}
                        aria-invalid={hasError ? true : false}
                        aria-describedby={hintId}
                        aria-expanded={open}
                        onClick={() => !disabled && setOpen((prev) => !prev)}
                        className={`flex h-11 w-full items-center justify-between rounded-lg border bg-white px-4 py-2 text-sm transition focus:outline-none focus:ring-1 ${stateClasses}`}
                    >
                        <span
                            className={
                                selected ? "text-gray-800" : "text-gray-400"
                            }
                        >
                            {selected ? selected.label : placeholder}
                        </span>

                        {!hideChevron && (
                            <LuChevronDown
                                className={`h-4 w-4 transition-transform ${open ? "rotate-180" : ""
                                    }`}
                            />
                        )}
                    </button>

                    {/* Dropdown */}
                    {open && !disabled && (
                        <ul className="absolute z-20 mt-2 w-full rounded-lg overflow-auto border border-gray-200 bg-white shadow-lg">
                            {options
                                .filter(opt => opt.value !== undefined && opt.value !== null)
                                .map((opt, index) => (
                                    <li
                                        key={`${opt.value}-${index}`}
                                        onClick={() => {
                                            onChange(opt.value);

                                            setInternalError(null);

                                            setOpen(false);
                                        }}
                                        className={`cursor-pointer px-4 py-2 text-sm transition hover:bg-secondary hover:text-white ${value === opt.value
                                            ? "bg-gray-100 font-medium"
                                            : ""
                                            }`}
                                    >
                                        {opt.label}
                                    </li>
                                ))}
                        </ul>
                    )}
                </div>

                {/* Hint / Error */}
                {(hint || error || internalError) && (
                    <p
                        id={hintId}
                        className={`text-xs ${hasError
                            ? "text-error-500"
                            : success
                                ? "text-success-500"
                                : "text-gray-500"
                            }`}
                    >
                        {error ?? internalError ?? hint}
                    </p>
                )}
            </div>
        );
    }
);

Select.displayName = "Select";
export default Select;
