import { useEffect, useRef, useId, useState } from "react";
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.css";
import { LuCalendar } from "react-icons/lu";

type ModeType = "single" | "multiple" | "range" | "time";

interface DatePickerProps {
    id?: string;
    name?: string;
    mode?: ModeType;
    value?: string;
    onChange?: (dateStr: string) => void;

    label?: string;
    placeholder?: string;

    required?: boolean;
    disabled?: boolean;

    hint?: string;
    error?: string;

    className?: string;
}

export default function DatePicker({
    id,
    name,
    mode = "single",
    value,
    onChange,
    label,
    placeholder,
    required = false,
    disabled = false,
    hint,
    error,
    className = "",
}: DatePickerProps) {
    const generatedId = useId();
    const inputId = id || generatedId;

    const inputRef = useRef<HTMLInputElement>(null);
    const flatpickrInstance = useRef<flatpickr.Instance | null>(null);

    const [touched, setTouched] = useState(false);

    const isInvalid = required && touched && !value;

    // Determine format berdasarkan mode
    const getFormat = () => {
        switch (mode) {
            case "time":
                return "H:i";
            case "range":
                return "Y-m-d";
            case "multiple":
                return "Y-m-d";
            default:
                return "Y-m-d";
        }
    };

    useEffect(() => {
        if (!inputRef.current) return;

        if (flatpickrInstance.current) {
            flatpickrInstance.current.destroy();
        }

        flatpickrInstance.current = flatpickr(inputRef.current, {
            mode,
            static: true,
            enableTime: mode === "time",
            noCalendar: mode === "time",
            time_24hr: true,
            monthSelectorType: "static",
            dateFormat: getFormat(),
            defaultDate: value,
            onChange: (_selectedDates, dateStr) => {
                setTouched(true);
                onChange?.(dateStr);
            },
        });

        return () => {
            flatpickrInstance.current?.destroy();
        };
    }, [mode]);

    // Sync ketika value berubah dari luar
    useEffect(() => {
        if (flatpickrInstance.current && value !== undefined) {
            flatpickrInstance.current.setDate(value, false);
        }
    }, [value]);

    const borderState =
        isInvalid || error
            ? "border-red-500 focus:border-red-500 focus:ring-red-200"
            : "border-gray-300 focus:border-primary focus:ring-brand-500/20";

    return (
        <div className={`w-full ${className}`}>
            {/* LABEL */}
            {label && (
                <label
                    htmlFor={inputId}
                    className="mb-1.5 block text-sm font-medium text-gray-700"
                >
                    {label}
                    {required && <span className="ml-1 text-red-500">*</span>}
                </label>
            )}

            <div className="relative">
                <input
                    id={inputId}
                    name={name}
                    ref={inputRef}
                    value={value || ""}
                    readOnly
                    disabled={disabled}
                    placeholder={placeholder}
                    className={`h-11 w-full rounded-lg border px-4 py-2.5 text-sm
            placeholder:text-gray-400
            focus:outline-none focus:ring-1
            disabled:bg-gray-100 disabled:cursor-not-allowed
            bg-transparent text-gray-800
            ${borderState}`}
                />

                <span className="absolute text-gray-500 -translate-y-1/2 pointer-events-none right-3 top-1/2">
                    <LuCalendar className="size-5" />
                </span>
            </div>

            {/* HINT / ERROR */}
            {(hint || isInvalid || error) && (
                <p
                    className={`mt-1 text-xs ${isInvalid || error ? "text-red-500" : "text-gray-500"
                        }`}
                >
                    {error
                        ? error
                        : isInvalid
                            ? "Field ini wajib diisi"
                            : hint}
                </p>
            )}
        </div>
    );
}
