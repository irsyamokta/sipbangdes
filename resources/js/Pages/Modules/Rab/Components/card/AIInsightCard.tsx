import usePermission from "@/hooks/usePermission";
import Button from "@/Components/ui/button/Button";

import { LuSparkles } from "react-icons/lu";
import { RiAiGenerate2 } from "react-icons/ri";
import { PiStarFour } from "react-icons/pi";

interface AIInsightCardProps {
    onGenerate?: () => void;
    hasInsight?: boolean;
    children?: React.ReactNode;
}

export const AIInsightCard = ({
    onGenerate,
    hasInsight = false,
    children,
}: AIInsightCardProps) => {
    const { can } = usePermission();

    const canGenerate = can("rab.create");

    return (
        <div className="border border-gray-300 rounded-2xl p-4 bg-white">
            {/* Header */}
            <div className="flex flex-col md:flex-row items-start justify-between gap-4">
                <div className="flex items-start gap-3">
                    <div className="mt-1 text-primary">
                        <RiAiGenerate2 size={22} />
                    </div>

                    <div>
                        <h3 className="font-semibold text-gray-900">
                            AI Insight Proyek
                        </h3>
                        <p className="text-sm text-gray-500">
                            Rangkuman analisis kebutuhan material, tenaga kerja, dan alat
                        </p>
                    </div>
                </div>

                {/* Button */}
                {canGenerate && (
                    <Button
                        onClick={onGenerate}
                        className="w-full md:w-auto flex items-center gap-2"
                        startIcon={<LuSparkles size={16} />}
                    >
                        Generate
                    </Button>
                )}
            </div>

            {/* Content */}
            <div className="mt-6">
                {!hasInsight ? (
                    <div className="flex flex-col justify-center items-center py-10">
                        <div className="flex justify-center items-center w-16 h-16 bg-gray-200 rounded-full mb-3">
                            <PiStarFour size={32} className="text-gray-600" />
                        </div>
                        <p className="font-semibold text-gray-900">Belum ada insight</p>
                        <p className="text-sm text-center text-gray-500">Klik tombol “Generate” untuk menganalisis kebutuhan proyek secara keseluruhan</p>
                    </div>
                ) : (
                    children
                )}
            </div>
        </div>
    );
};
