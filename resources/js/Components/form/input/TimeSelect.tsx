import React, {
    useRef,
    useEffect,
    useState,
    useMemo,
    useId,
} from "react";

interface TimeSelectProps {
    name?: string;
    label?: string;
    value?: string;
    onChange: (val: string) => void;
    disabled?: boolean;
    required?: boolean;
    hint?: string;
    error?: string;
    className?: string;
}

const TimeSelect: React.FC<TimeSelectProps> = ({
    name,
    label,
    value = "",
    onChange,
    disabled = false,
    required = false,
    hint,
    error,
    className = "",
}) => {
    const id = useId();

    const [isHourOpen, setIsHourOpen] = useState(false);
    const [isMinuteOpen, setIsMinuteOpen] = useState(false);
    const [touched, setTouched] = useState(false);

    const hourRef = useRef<HTMLDivElement>(null);
    const minuteRef = useRef<HTMLDivElement>(null);
    const hourContainerRef = useRef<HTMLDivElement>(null);
    const minuteContainerRef = useRef<HTMLDivElement>(null);

    const [hour, minute] = useMemo(() => {
        if (!value) return ["", ""];
        const parts = value.split(":");
        return [parts[0] ?? "", parts[1] ?? ""];
    }, [value]);

    const isInvalid = required && touched && !value;

    const hours = useMemo(
        () =>
            Array.from({ length: 24 }, (_, i) => {
                const h = i.toString().padStart(2, "0");
                return { value: h, label: h };
            }),
        []
    );

    const minutes = useMemo(
        () =>
            Array.from({ length: 60 }, (_, i) => {
                const m = i.toString().padStart(2, "0");
                return { value: m, label: m };
            }),
        []
    );

    useEffect(() => {
        if (isHourOpen && hourRef.current) {
            hourRef.current
                .querySelector(`[data-value="${hour}"]`)
                ?.scrollIntoView({ behavior: "smooth", block: "center" });
        }

        if (isMinuteOpen && minuteRef.current) {
            minuteRef.current
                .querySelector(`[data-value="${minute}"]`)
                ?.scrollIntoView({ behavior: "smooth", block: "center" });
        }
    }, [isHourOpen, isMinuteOpen, hour, minute]);

    useEffect(() => {
        const handleClickOutside = (event: MouseEvent) => {
            if (
                hourContainerRef.current &&
                !hourContainerRef.current.contains(event.target as Node)
            ) {
                setIsHourOpen(false);
            }

            if (
                minuteContainerRef.current &&
                !minuteContainerRef.current.contains(event.target as Node)
            ) {
                setIsMinuteOpen(false);
            }
        };

        document.addEventListener("mousedown", handleClickOutside);
        return () =>
            document.removeEventListener("mousedown", handleClickOutside);
    }, []);

    const handleHourChange = (h: string) => {
        onChange(`${h}:${minute || "00"}`);
        setTouched(true);
        setIsHourOpen(false);
    };

    const handleMinuteChange = (m: string) => {
        onChange(`${hour || "00"}:${m}`);
        setTouched(true);
        setIsMinuteOpen(false);
    };

    const borderState = isInvalid || error
        ? "border-red-500 focus:border-red-500 focus:ring-red-200"
        : "border-gray-300 focus:border-primary focus:ring-primary/20";

    return (
        <div className={`w-full ${className}`}>
            {/* LABEL */}
            {label && (
                <label
                    htmlFor={id}
                    className="mb-1.5 block text-sm font-medium text-gray-700"
                >
                    {label}
                    {required && (
                        <span className="ml-1 text-red-500">*</span>
                    )}
                </label>
            )}

            <div className="flex items-center gap-1">
                {/* Hidden input untuk form submit */}
                <input
                    type="hidden"
                    id={id}
                    name={name}
                    value={value}
                    disabled={disabled}
                />

                {/* Hour */}
                <div ref={hourContainerRef} className="relative w-14">
                    <input
                        type="text"
                        value={hour}
                        readOnly
                        disabled={disabled}
                        onClick={() => !disabled && setIsHourOpen((prev) => !prev)}
                        onBlur={() => setTouched(true)}
                        className={`h-11 w-full rounded-lg border px-4 py-2.5 text-sm
              focus:outline-none focus:ring-1
              disabled:bg-gray-100 disabled:cursor-not-allowed
              cursor-pointer ${borderState}`}
                    />

                    {isHourOpen && !disabled && (
                        <div className="absolute z-10 top-full mt-1 w-full max-h-40 bg-white border rounded-md shadow-lg overflow-y-auto no-scrollbar">
                            <div ref={hourRef} className="flex flex-col py-8">
                                {hours.map((h) => (
                                    <button
                                        type="button"
                                        key={h.value}
                                        data-value={h.value}
                                        onClick={() => handleHourChange(h.value)}
                                        className={`w-full py-1.5 text-sm font-medium transition-all
                      ${hour === h.value
                                                ? "text-primary bg-blue-100"
                                                : "text-gray-600 hover:bg-gray-100"
                                            }`}
                                    >
                                        {h.label}
                                    </button>
                                ))}
                            </div>
                        </div>
                    )}
                </div>

                <span className="text-gray-600 text-sm font-medium">:</span>

                {/* Minute */}
                <div ref={minuteContainerRef} className="relative w-14">
                    <input
                        type="text"
                        value={minute}
                        readOnly
                        disabled={disabled}
                        onClick={() => !disabled && setIsMinuteOpen((prev) => !prev)}
                        onBlur={() => setTouched(true)}
                        className={`h-11 w-full rounded-lg border px-4 py-2.5 text-sm
              focus:outline-none focus:ring-1
              disabled:bg-gray-100 disabled:cursor-not-allowed
              cursor-pointer ${borderState}`}
                    />

                    {isMinuteOpen && !disabled && (
                        <div className="absolute z-10 top-full mt-1 w-full max-h-40 bg-white border rounded-md shadow-lg overflow-y-auto no-scrollbar">
                            <div ref={minuteRef} className="flex flex-col py-8">
                                {minutes.map((m) => (
                                    <button
                                        type="button"
                                        key={m.value}
                                        data-value={m.value}
                                        onClick={() => handleMinuteChange(m.value)}
                                        className={`w-full py-1.5 text-sm font-medium transition-all
                      ${minute === m.value
                                                ? "text-primary bg-blue-100"
                                                : "text-gray-600 hover:bg-gray-100"
                                            }`}
                                    >
                                        {m.label}
                                    </button>
                                ))}
                            </div>
                        </div>
                    )}
                </div>
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
};

TimeSelect.displayName = "TimeSelect";

export default TimeSelect;
