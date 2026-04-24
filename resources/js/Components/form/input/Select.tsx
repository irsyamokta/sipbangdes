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
    disabled?: boolean;
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

        const [open, setOpen] = useState(false);
        const [search, setSearch] = useState("");

        const [internalError, setInternalError] = useState<string | null>(null);

        const wrapperRef = useRef<HTMLDivElement>(null);

        const hasError = error || !!internalError;

        const selected = options.find((opt) => opt.value === value);

        useEffect(() => {
            const handleClickOutside = (event: MouseEvent) => {
                if (
                    wrapperRef.current &&
                    !wrapperRef.current.contains(event.target as Node)
                ) {
                    setOpen(false);
                    setSearch("");
                }
            };

            document.addEventListener("mousedown", handleClickOutside);
            return () => {
                document.removeEventListener("mousedown", handleClickOutside);
            };
        }, []);

        const filteredOptions = options.filter((opt) =>
            opt.label.toLowerCase().includes(search.toLowerCase())
        );

        const stateClasses = disabled
            ? "bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed"
            : hasError
                ? "border-error-500 focus:ring-error-500/20"
                : success
                    ? "border-success-500 focus:ring-success-500/20"
                    : "border-gray-300 focus:ring-primary/20 focus:border-primary";

        return (
            <div ref={wrapperRef} className={`w-full space-y-1.5 ${className}`}>
                {/* Label */}
                {label && (
                    <label className="block text-sm font-medium text-gray-800">
                        {label}
                        {required && <span className="text-error-500"> *</span>}
                    </label>
                )}

                <div className="relative">

                    {/* Trigger / Input */}
                    <div
                        className={`flex h-11 w-full items-center justify-between rounded-lg border px-3 py-2 text-sm ${stateClasses}`}
                        onClick={() => !disabled && setOpen(true)}
                    >
                        <input
                            ref={ref as any}
                            disabled={disabled}
                            value={open ? search : selected?.label ?? ""}
                            placeholder={placeholder}
                            onChange={(e) => {
                                setSearch(e.target.value);
                                if (!open) setOpen(true);
                            }}
                            className="w-full outline-none bg-transparent text-sm"
                        />

                        {!hideChevron && (
                            <LuChevronDown
                                className={`h-4 w-4 transition-transform ${
                                    open ? "rotate-180" : ""
                                }`}
                            />
                        )}
                    </div>

                    {/* Dropdown */}
                    {open && !disabled && (
                        <ul
                            className="absolute z-20 mt-2 w-full rounded-lg border border-gray-200 bg-white shadow-lg overflow-y-auto"
                            style={{ maxHeight: `${3 * 40}px` }}
                        >
                            {filteredOptions.length === 0 ? (
                                <li className="px-4 py-2 text-sm text-gray-400">
                                    Tidak ada data
                                </li>
                            ) : (
                                filteredOptions.map((opt, index) => {
                                    const isDisabled = opt.disabled;

                                    return (
                                        <li
                                            key={`${opt.value}-${index}`}
                                            onClick={() => {
                                                if (isDisabled) return;
                                                onChange(opt.value);
                                                setOpen(false);
                                                setSearch("");
                                                setInternalError(null);
                                            }}
                                            className={`
                                                px-4 py-2 text-sm transition
                                                ${
                                                    isDisabled
                                                        ? "cursor-not-allowed text-gray-400"
                                                        : "cursor-pointer hover:bg-secondary hover:text-white"
                                                }
                                                ${
                                                    value === opt.value
                                                        ? "bg-gray-100 font-medium"
                                                        : ""
                                                }
                                            `}
                                        >
                                            {opt.label}
                                        </li>
                                    );
                                })
                            )}
                        </ul>
                    )}
                </div>

                {(hint || error || internalError) && (
                    <p
                        className={`text-xs ${
                            hasError
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