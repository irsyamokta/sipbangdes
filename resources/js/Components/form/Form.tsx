import { FC, ReactNode, FormEvent, KeyboardEvent } from "react";

interface FormProps {
    id?: string;
    onSubmit: (event: FormEvent<HTMLFormElement>) => void;
    children: ReactNode;
    className?: string;

    preventEnterSubmit?: boolean;
    onKeyDown?: (event: KeyboardEvent<HTMLFormElement>) => void;
}

const Form: FC<FormProps> = ({
    id,
    onSubmit,
    children,
    className,
    preventEnterSubmit = false,
    onKeyDown,
}) => {
    const handleKeyDown = (e: KeyboardEvent<HTMLFormElement>) => {
        if (
            preventEnterSubmit &&
            e.key === "Enter" &&
            (e.target as HTMLElement).tagName !== "TEXTAREA"
        ) {
            e.preventDefault();
        }

        onKeyDown?.(e);
    };

    return (
        <form
            id={id}
            onSubmit={(event) => {
                event.preventDefault();
                onSubmit(event);
            }}
            className={className}
            onKeyDown={handleKeyDown}
        >
            {children}
        </form>
    );
};

export default Form;
