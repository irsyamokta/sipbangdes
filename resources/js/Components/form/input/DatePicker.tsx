import { useEffect, useRef, useId, useState } from "react";
import flatpickr from "flatpickr";
import { Instance } from "flatpickr/dist/types/instance";
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
    const flatpickrInstance = useRef<Instance | null>(null);

    const [touched, setTouched] = useState(false);

    const isInvalid = required && touched && !value;

    const getFormat = () => {
        if (mode === "time") return "H:i";
        return "Y-m-d";
    };

    const parseLocalDate = (
        datestr: string,
        format: string
    ): Date => {
        if (!datestr) return new Date();

        if (mode === "time") {
            return flatpickr.parseDate(
                datestr,
                format
            ) as Date;
        }

        const parts = datestr.split("-");

        if (parts.length !== 3) {
            return new Date();
        }

        const [y, m, d] = parts.map(Number);

        return new Date(y, m - 1, d);
    };

    const formatLocalDate = (
        date: Date,
        format: string
    ): string => {
        if (mode === "time") {
            return flatpickr.formatDate(
                date,
                format
            );
        }

        const y = date.getFullYear();
        const m = String(date.getMonth() + 1).padStart(2, "0");
        const d = String(date.getDate()).padStart(2, "0");

        return `${y}-${m}-${d}`;
    };

    useEffect(() => {
        if (!inputRef.current) return;

        if (flatpickrInstance.current) {
            flatpickrInstance.current.destroy();
        }

        flatpickrInstance.current = flatpickr(
            inputRef.current,
            {
                mode,
                static: true,

                enableTime: mode === "time",
                noCalendar: mode === "time",
                time_24hr: true,

                monthSelectorType: "static",

                dateFormat: getFormat(),

                parseDate: parseLocalDate,

                formatDate: formatLocalDate,

                defaultDate: value || undefined,

                onChange: (
                    _selectedDates,
                    dateStr
                ) => {
                    setTouched(true);
                    onChange?.(dateStr);
                },
            }
        );

        return () => {
            flatpickrInstance.current?.destroy();
        };
    }, [mode]);

    /**
     * Sync external value
     */
    useEffect(() => {
        if (
            flatpickrInstance.current &&
            value
        ) {
            flatpickrInstance.current.setDate(
                value,
                false
            );
        }
    }, [value]);

    const borderState = error
        ? "border-red-500 focus:border-red-500 focus:ring-red-200"
        : "border-gray-300 focus:border-primary focus:ring-brand-500/20";

    return (
        <div className={`w-full ${className}`}>
            {label && (
                <label
                    htmlFor={inputId}
                    className="mb-1.5 block text-sm font-medium text-gray-700"
                >
                    {label}
                    {required && (
                        <span className="ml-1 text-red-500">
                            *
                        </span>
                    )}
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

            {(hint || isInvalid || error) && (
                <p
                    className={`mt-1 text-xs ${
                        isInvalid || error
                            ? "text-red-500"
                            : "text-gray-500"
                    }`}
                >
                    {error ? error : hint}
                </p>
            )}
        </div>
    );
}