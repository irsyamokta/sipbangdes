import { useState, useEffect } from "react";

interface UseBudgetYearsOptions {
    startYear?: number;
    range?: number; // jumlah tahun ke depan
}

interface YearOption {
    value: number;
    label: string;
}

export const useBudgetYears = ({
    startYear,
    range = 1, // default 1 tahun ke depan
}: UseBudgetYearsOptions = {}): YearOption[] => {
    const [years, setYears] = useState<YearOption[]>([]);

    useEffect(() => {
        const updateYears = () => {
            const currentYear = new Date().getFullYear();
            const from = startYear ?? currentYear;
            const to = currentYear + range - 1; // selalu mengikuti tahun berjalan

            const options: YearOption[] = [];
            for (let year = from; year <= to; year++) {
                options.push({ value: year, label: String(year) });
            }
            setYears(options);
        };

        updateYears();

        const interval = setInterval(updateYears, 1000 * 60 * 60 * 24);

        return () => clearInterval(interval);
    }, [startYear, range]);

    return years;
};
