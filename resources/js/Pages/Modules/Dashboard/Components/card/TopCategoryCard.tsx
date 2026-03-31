import { Link } from "@inertiajs/react";

import { TopCategory } from "@/types/dashboard";

import Button from "@/Components/ui/button/Button";
import { EmptyTable } from "@/Components/empty/EmptyTable";

import { GoChevronRight } from "react-icons/go";

interface Props {
    categories: TopCategory[];
}

const TopCategoryCard = ({ categories }: Props) => {
    const isEmpty = !categories || categories.length === 0;

    return (
        <div className="bg-white border border-gray-300 rounded-2xl p-4">
            {/* Header */}
            <div className="flex justify-between items-center mb-4">
                <h2 className="font-semibold text-gray-800">
                    Kategori Pekerjaan
                </h2>

                <Link href={route("workercategory.index")}>
                    <Button
                        size="none"
                        variant="link"
                        endIcon={<GoChevronRight size={18} />}
                        className="text-sm"
                    >
                        Lihat Semua
                    </Button>
                </Link>
            </div>

            {/* Content */}
            {isEmpty ? (
                <div className="py-6">
                    <EmptyTable
                        colspan={1}
                        description="Belum ada kategori"
                    />
                </div>
            ) : (
                <div className="space-y-3">
                    {categories.map((category, index) => (
                        <div
                            key={index}
                            className="flex items-center justify-between bg-gray-100 rounded-xl px-4 py-3"
                        >
                            {/* Left */}
                            <div className="flex items-center gap-3">
                                {/* Dot */}
                                <div className="w-2.5 h-2.5 bg-blue-900 rounded-full" />

                                <span className="text-gray-800 font-medium line-clamp-1">
                                    {category.name}
                                </span>
                            </div>

                            {/* Right */}
                            <span className="text-sm text-gray-500 whitespace-nowrap">
                                {category.total_items} item
                            </span>
                        </div>
                    ))}
                </div>
            )}
        </div>
    );
};

export default TopCategoryCard;
