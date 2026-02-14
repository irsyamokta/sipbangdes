interface ModalTitleProps {
    title: string;
    subtitle?: string;
}

const ModalTitle = ({ title, subtitle }: ModalTitleProps) => {
    return (
        <div className="mb-4">
            <h4 className="text-2xl font-semibold text-gray-800 dark:text-white/90">
                {title}
            </h4>

            {subtitle && (
                <p className="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {subtitle}
                </p>
            )}
        </div>
    );
};

export default ModalTitle;
