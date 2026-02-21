import Input from "@/Components/form/input/InputField";
import Select from "@/Components/form/input/Select";
import { FiSearch } from "react-icons/fi";

type SelectOption = {
    value: string | number;
    label: string;
};

type FilterBarProps = {
    search?: {
        value: string;
        placeholder?: string;
        onChange: (value: string) => void;
    };
    select?: {
        value: string;
        options: SelectOption[];
        placeholder?: string;
        onChange: (value: string) => void;
    };
    className?: string;
};

export default function FilterBar({
    search,
    select,
    className = "",
}: FilterBarProps) {
    return (
        <div className={`flex flex-col sm:flex-row items-center gap-2 w-full lg:w-1/2 ${className}`}>

            {select && (
                <Select
                    value={select.value}
                    options={select.options}
                    placeholder={select.placeholder}
                    onChange={(value) => select.onChange(value as string)}
                    className="w-full rounded-lg border-gray-300 text-sm"
                />
            )}

            {search && (
                <Input
                    type="text"
                    startIcon={<FiSearch />}
                    placeholder={search.placeholder ?? "Cari..."}
                    value={search.value}
                    onChange={(e) => search.onChange(e.target.value)}
                    className="w-full rounded-lg border-gray-300 text-sm"
                />
            )}
        </div>
    );
}
