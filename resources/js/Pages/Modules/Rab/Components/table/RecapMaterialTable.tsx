import { RabMaterialRecap } from "@/types/rab";

import {
    Table,
    TableBody,
    TableCell,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";

import { EmptyTable } from "@/Components/empty/EmptyTable";

import { formatCurrency } from "@/utils/formatCurrrency";
import { formatDecimal } from "@/utils/formatDecimal";

import { PiPackage } from "react-icons/pi";

interface RecapMaterialTableProps {
    materials: RabMaterialRecap[];
}

export const RecapMaterialTable = ({ materials }: RecapMaterialTableProps) => {
    const totalMaterial = materials.reduce(
        (sum, item) => sum + item.total,
        0
    );

    return (
        <div className="flex flex-col gap-4 py-2 mt-4">
            {/* Card */}
            <div className="overflow-hidden rounded-xl bg-white border border-gray-300">

                {/* Header */}
                <div className="flex gap-2 items-center bg-green-100 px-4 py-3 border-b border-gray-300">
                    <PiPackage size={20} className="text-green-700" />
                    <p className="font-semibold text-gray-800">
                        Rekap Kebutuhan Material
                    </p>
                </div>

                {/* Table */}
                <div className="max-w-full overflow-x-auto">
                    <Table>
                        {/* Header */}
                        <TableHeader className="bg-gray-200 border-b">
                            <TableRow>
                                <TableCell isHeader className="px-6 py-3 text-xs text-start text-gray-900 whitespace-nowrap">
                                    Nama Material
                                </TableCell>

                                <TableCell isHeader className="px-6 py-3 text-xs text-end text-gray-900">
                                    Satuan
                                </TableCell>

                                <TableCell isHeader className="px-6 py-3 text-xs text-end text-gray-900 whitespace-nowrap">
                                    Kebutuhan
                                </TableCell>

                                <TableCell isHeader className="px-6 py-3 text-xs text-end text-gray-900 whitespace-nowrap">
                                    Harga Satuan
                                </TableCell>

                                <TableCell isHeader className="px-6 py-3 text-xs text-end text-gray-900 whitespace-nowrap">
                                    Total Harga
                                </TableCell>
                            </TableRow>
                        </TableHeader>

                        {/* Body */}
                        <TableBody className="divide-y divide-gray-100">
                            {materials.length === 0 ? (
                                <EmptyTable
                                    colspan={5}
                                    description="Belum ada data material"
                                />
                            ) : (
                                materials.map((item, index) => (
                                    <TableRow
                                        key={`${item.name}-${index}`}
                                        className="hover:bg-gray-50 transition"
                                    >
                                        {/* Nama */}
                                        <TableCell className="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                                            {item.name}
                                        </TableCell>

                                        {/* Satuan */}
                                        <TableCell className="px-6 py-4 text-sm text-end text-gray-900">
                                            {item.unit}
                                        </TableCell>

                                        {/* Kebutuhan */}
                                        <TableCell className="px-6 py-4 text-sm text-end text-gray-900 whitespace-nowrap">
                                            {formatDecimal(item.quantity)}
                                        </TableCell>

                                        {/* Harga */}
                                        <TableCell className="px-6 py-4 text-sm text-end text-gray-900 whitespace-nowrap">
                                            {formatCurrency(item.price)}
                                        </TableCell>

                                        {/* Total */}
                                        <TableCell className="px-6 py-4 text-sm text-end text-gray-900 font-semibold whitespace-nowrap">
                                            {formatCurrency(item.total)}
                                        </TableCell>
                                    </TableRow>
                                ))
                            )}
                        </TableBody>
                    </Table>
                </div>

                {/* Footer Total */}
                {materials.length > 0 && (
                    <div className="flex justify-end items-center gap-4 px-6 py-3 bg-green-100 border-t border-gray-300">
                        <p className="text-sm font-semibold text-gray-800">
                            Total Material
                        </p>
                        <p className="text-sm font-bold text-green-700">
                            {formatCurrency(totalMaterial)}
                        </p>
                    </div>
                )}
            </div>
        </div>
    );
};
