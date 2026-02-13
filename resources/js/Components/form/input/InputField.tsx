import React, {
    forwardRef,
    useId,
    useState,
} from "react";
import { VscEye, VscEyeClosed } from "react-icons/vsc";

type NativeInputProps = React.ComponentPropsWithoutRef<"input">;

interface InputProps extends NativeInputProps {
    label?: string;
    hint?: string;
    error?: string;
    success?: boolean;
    startIcon?: React.ReactNode;
    endIcon?: React.ReactNode;
    required?: boolean;
    optional?: boolean;
    enablePasswordValidation?: boolean;
}

const Input = forwardRef<HTMLInputElement, InputProps>(
    (
        {
            label,
            hint,
            error,
            success = false,
            startIcon,
            endIcon,
            optional = false,
            className = "",
            type = "text",
            disabled,
            id,
            required,
            onBlur,
            onChange,
            enablePasswordValidation = true,

            ...props
        },
        ref
    ) => {
        const generatedId = useId();
        const inputId = id ?? generatedId;

        const [showPassword, setShowPassword] = useState(false);
        const [internalError, setInternalError] = useState<string | null>(null);

        const isPassword = type === "password";

        const computedType =
            isPassword && showPassword ? "text" : type;

        const passwordRegex =
            /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).{8,}$/;

        const validatePassword = (value: string) => {
            if (!value.trim()) return "Password wajib diisi";

            if (!passwordRegex.test(value)) {
                return "Password minimal 8 karakter, harus ada huruf besar, huruf kecil, angka, dan simbol";
            }

            return null;
        };

        const baseClasses =
            "h-11 w-full rounded-lg border text-sm shadow-theme-xs transition-all duration-200 placeholder:text-gray-400 focus:outline-none focus:ring-1 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30";

        const stateClasses = disabled
            ? "text-gray-500 border-gray-300 bg-gray-100 cursor-not-allowed opacity-60"
            : error || internalError
            ? "border-error-500 focus:border-error-300 focus:ring-error-500/20"
            : success
            ? "border-success-500 focus:border-success-300 focus:ring-success-500/20"
            : "bg-transparent text-gray-800 border-gray-300 focus:border-primary focus:ring-primary/20";

        const paddingLeft = startIcon ? "pl-9" : "pl-4";
        const paddingRight =
            endIcon || isPassword ? "pr-11" : "pr-4";

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
                                (Opsional)
                            </span>
                        )}
                    </label>
                )}

                {/* Input */}
                <div className="relative">
                    {startIcon && (
                        <span className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            {startIcon}
                        </span>
                    )}

                    <input
                        id={inputId}
                        ref={ref}
                        type={computedType}
                        disabled={disabled}
                        aria-invalid={!!error || !!internalError}

                        onChange={(e) => {
                            const value = e.target.value;

                            // Required realtime
                            if (required && value.trim() === "") {
                                setInternalError("Field ini wajib diisi");
                            }

                            // Password regex hanya kalau diaktifkan
                            else if (
                                isPassword &&
                                enablePasswordValidation
                            ) {
                                setInternalError(validatePassword(value));
                            }

                            // Clear error normal
                            else {
                                setInternalError(null);
                            }

                            onChange?.(e);
                        }}

                        // Blur fallback
                        onBlur={(e) => {
                            const value = e.target.value;

                            if (required && !value.trim()) {
                                setInternalError("Field ini wajib diisi");
                            }

                            onBlur?.(e);
                        }}

                        className={[
                            baseClasses,
                            stateClasses,
                            paddingLeft,
                            paddingRight,
                            className,
                        ].join(" ")}

                        {...props}
                    />

                    {/* End Icon */}
                    {endIcon && !isPassword && (
                        <span className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                            {endIcon}
                        </span>
                    )}

                    {/* Toggle Password */}
                    {isPassword && (
                        <button
                            type="button"
                            onClick={() =>
                                setShowPassword((prev) => !prev)
                            }
                            className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400"
                            tabIndex={-1}
                        >
                            {showPassword ? (
                                <VscEyeClosed size={18} />
                            ) : (
                                <VscEye size={18} />
                            )}
                        </button>
                    )}
                </div>

                {/* Hint / Error */}
                {(hint || error || internalError) && (
                    <p
                        className={`text-xs ${
                            error || internalError
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

Input.displayName = "Input";
export default Input;
