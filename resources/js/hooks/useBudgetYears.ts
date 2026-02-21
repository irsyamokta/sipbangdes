import { useMemo } from "react";

interface UseBudgetYearsOptions {
    startYear?: number;
    range?: number;
    endYear?: number;
}

interface YearOption {
    value: number;
    label: string;
}

export const useBudgetYears = ({
    startYear,
    range = 10,
    endYear,
}: UseBudgetYearsOptions = {}): YearOption[] => {
    return useMemo(() => {
        const currentYear = new Date().getFullYear();
        const from = startYear ?? currentYear;

        let to: number;

        if (endYear) {
            to = endYear;
        } else {
            to = from + range - 1;
        }

        const length = to - from + 1;

        return Array.from({ length }, (_, i) => {
            const year = from + i;
            return {
                value: year,
                label: String(year),
            };
        });
    }, [startYear, range, endYear]);
};
