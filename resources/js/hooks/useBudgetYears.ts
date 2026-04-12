import { useState, useEffect } from "react";

interface UseBudgetYearsOptions {
    startYear?: number;
    range?: number;
    includeAll?: boolean;
}

interface YearOption {
    value: number | string;
    label: string;
}

export const useBudgetYears = ({
    startYear,
    range = 1,
    includeAll = false,
}: UseBudgetYearsOptions = {}): YearOption[] => {
    const [years, setYears] = useState<YearOption[]>([]);

    useEffect(() => {
        const updateYears = () => {
            const currentYear = new Date().getFullYear();
            const from = startYear ?? currentYear;
            const to = currentYear + range - 1;

            const options: YearOption[] = [];

            if (includeAll) {
                options.push({
                    value: "",
                    label: "Semua Tahun",
                });
            }

            for (let year = from; year <= to; year++) {
                options.push({
                    value: year,
                    label: String(year),
                });
            }

            setYears(options);
        };

        updateYears();

        const interval = setInterval(updateYears, 1000 * 60 * 60 * 24);

        return () => clearInterval(interval);

    }, [startYear, range, includeAll]);

    return years;
};