import { toast } from "sonner";

import usePermission from "@/hooks/usePermission";
import { useModalForm } from "@/hooks/useModalForm";

import { RabInsight } from "@/types/rab";

import Button from "@/Components/ui/button/Button";

import { parseInsight, renderBoldText } from "@/utils/parseInsight";

import { LuSparkles, LuLoader } from "react-icons/lu";
import { RiAiGenerate2 } from "react-icons/ri";
import { PiStarFour } from "react-icons/pi";
import { FcIdea } from "react-icons/fc";

interface AIInsightCardProps {
    projectId: string;
    insight: RabInsight | null;
    hasInsight?: boolean;
}

const AIInsightCard = ({
    projectId,
    insight,
    hasInsight
}: AIInsightCardProps) => {
    const { can } = usePermission();
    const sections = parseInsight(insight?.insight_content);

    if (!projectId) {
        toast.error("Project belum dipilih");
        return;
    }

    const {
        handleSubmit,
        loading,
    } = useModalForm<RabInsight>({
        isOpen: !!insight,
        onClose: () => { },
        initialValues: {
            project_id: projectId,
        },
        successMessage: "Insight berhasil disimpan",
        errorMessage: "Insight gagal disimpan",
        storeRoute: "rab.insight.store",
    });

    return (
        <div className="border border-gray-300 rounded-2xl p-4 bg-whit mt-4">
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
                {can("rab.create") && (
                    <Button
                        onClick={handleSubmit}
                        className="w-full md:w-auto flex items-center gap-2"
                        startIcon={
                            loading ? (
                                <LuLoader size={16} className="animate-spin" />
                            ) : (
                                <LuSparkles size={16} />
                            )
                        }
                        disabled={loading}
                    >
                        {loading ? "Generating..." : hasInsight ? "Generate Ulang" : "Generate"}
                    </Button>
                )}
            </div>

            {/* Content */}
            <div className="mt-6 max-h-100 overflow-y-auto pr-2">
                {!hasInsight ? (
                    <div className="flex flex-col justify-center items-center py-10">
                        <div className="flex justify-center items-center w-16 h-16 bg-gray-200 rounded-full mb-3">
                            <PiStarFour size={32} className="text-gray-600" />
                        </div>
                        <p className="font-semibold text-gray-900">Belum ada insight</p>
                        <p className="text-sm text-center text-gray-500">Klik tombol “Generate” untuk menganalisis kebutuhan proyek secara keseluruhan</p>
                        <p className="text-sm text-center text-gray-500">Generate insight dibatasi sebanyak 3 kali</p>
                    </div>
                ) : (
                    <div className="space-y-6">
                        {sections.map((sec, i) => (
                            <div key={i}>
                                {/* Title */}
                                <h2 className="text-lg font-semibold text-gray-900 mb-2">
                                    {sec.title}
                                </h2>

                                {/* Points */}
                                <ul className="list-disc pl-5 space-y-1 text-gray-700">
                                    {sec.points.map((point, idx) => (
                                        <li key={idx}>
                                            {renderBoldText(point)}
                                        </li>
                                    ))}
                                </ul>
                            </div>
                        ))}
                    </div>
                )}
            </div>

            {/* Footer */}
            <div className="flex items-center justify-center gap-2 text-center text-sm text-gray-500 p-4 mt-3" hidden={!hasInsight}>
                <FcIdea size={16} className="hidden md:block"/>
                <p>Insight ini bersifat rekomendasi berdasarkan data RAB dan tidak mengubah data apapun</p>
            </div>
        </div>
    );
};

export default AIInsightCard;
