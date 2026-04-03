import { formatCurrency } from "@/utils/formatCurrrency";

interface CardSummaryProps {
    material_total: number;
    wage_total: number;
    tool_total: number;
}

const SummaryCard = ({
    material_total,
    wage_total,
    tool_total,
}: CardSummaryProps) => {
    return (
        <div className="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 py-2">
            <div className="bg-green-100 p-4 rounded-xl">
                <p className="text-sm text-gray-500">Material</p>
                <h1 className="text-green-700 font-semibold">{formatCurrency(material_total)}</h1>
            </div>
            <div className="bg-warning-50 p-4 rounded-xl">
                <p className="text-sm text-gray-500">Upah</p>
                <h1 className="text-warning-600 font-semibold">{formatCurrency(wage_total)}</h1>
            </div>
            <div className="bg-blue-50 p-4 rounded-xl">
                <p className="text-sm text-gray-500">Alat</p>
                <h1 className="text-blue-800 font-semibold">{formatCurrency(tool_total)}</h1>
            </div>
        </div>
    )
}

export default SummaryCard;
