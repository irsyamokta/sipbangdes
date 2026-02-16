import { ReactNode } from "react";

interface ButtonProps {
    form?: string;
    type?: "button" | "submit" | "reset";
    children?: ReactNode;

    size?: "xs" | "sm" | "md" | "lg" | "icon";
    variant?:
    | "primary"
    | "secondary"
    | "outline"
    | "outlineDash"
    | "ghost"
    | "danger"
    | "edit"
    | "link";

    startIcon?: ReactNode;
    endIcon?: ReactNode;

    onClick?: () => void;
    disabled?: boolean;
    fullWidth?: boolean;

    className?: string;
}

const BASE_CLASSES =
    "inline-flex items-center justify-center gap-2 font-medium transition-all duration-200 focus:outline-none disabled:cursor-not-allowed disabled:opacity-50";

const SIZE_CLASSES: Record<NonNullable<ButtonProps["size"]>, string> = {
    xs: "h-8 px-3 text-xs rounded-md",
    sm: "h-9 px-4 text-sm rounded-md",
    md: "h-10 px-5 text-sm rounded-lg",
    lg: "h-12 px-6 text-base rounded-lg",
    icon: "h-10 w-10 rounded-lg",
};

const VARIANT_CLASSES: Record<
    NonNullable<ButtonProps["variant"]>,
    string
> = {
    primary:
        "bg-primary text-white hover:bg-primary/90 focus:ring-brand-500",
    secondary:
        "bg-secondary text-white hover:bg-secondary/90 focus:ring-secondary",
    outline:
        "border border-primary bg-transparent hover:bg-gray-100 focus:ring-gray-400",
    outlineDash:
        "border border-dashed border-primary bg-transparent hover:bg-primary/5 focus:ring-primary",
    ghost:
        "bg-transparent hover:bg-gray-100 text-gray-700",
    danger:
        "bg-transparent text-red-700 hover:bg-red-50",
    edit:
        "bg-transparent text-blue-700 hover:bg-blue-50",
    link:
        "bg-transparent text-primary hover:underline shadow-none px-0 h-auto",
};

const Button = ({
    form,
    type = "button",
    children,
    size = "md",
    variant = "primary",
    startIcon,
    endIcon,
    onClick,
    disabled = false,
    fullWidth = false,
    className = "",
}: ButtonProps) => {
    return (
        <button
            {...(form ? { form } : {})}
            type={type}
            onClick={onClick}
            disabled={disabled}
            className={[
                BASE_CLASSES,
                SIZE_CLASSES[size],
                VARIANT_CLASSES[variant],
                fullWidth ? "w-full" : "",
                className,
            ].join(" ")}
        >
            {startIcon && <span className="flex items-center">{startIcon}</span>}
            {children && <span>{children}</span>}
            {endIcon && <span className="flex items-center">{endIcon}</span>}
        </button>
    );
};

export default Button;
