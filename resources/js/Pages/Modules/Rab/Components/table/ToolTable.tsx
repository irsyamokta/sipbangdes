import { ToolTableProps } from "@/types/rab";

import {
    Table,
    TableBody,
    TableCell,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";

import { formatCurrency } from "@/utils/formatCurrrency";
import { formatDecimal } from "@/utils/formatDecimal";
import { capitalizeEachWord } from "@/utils/capitalize";

import { LiaToolsSolid } from "react-icons/lia";

const ToolTable = ({
    tools
}: ToolTableProps) => {

    return (
        <div className="flex flex-col gap-4 py-2 mt-4">

            {/* Header */}
            <div className="flex gap-2 justify-between items-center">
                <div className="flex gap-2 items-center">
                    <LiaToolsSolid size={24} className="text-blue-700" />
                    <p className="font-semibold">Alat</p>
                </div>
            </div>
            {tools.length === 0 ? (
                <p className="text-gray-500 -mt-1">Belum ada komponen alat</p>
            ) : (
                <div className="overflow-hidden">
                    {/* Table */}
                    <div className="max-w-full overflow-x-auto">
                        <Table>
                            {/* Header */}
                            <TableHeader className="bg-gray-200">
                                <TableRow>
                                    <TableCell
                                        isHeader
                                        className="px-6 py-3 text-sm font-semibold text-gray-900 text-start whitespace-nowrap"
                                    >
                                        Nama Alat
                                    </TableCell>

                                    <TableCell
                                        isHeader
                                        className="px-6 py-3 text-sm font-semibold text-gray-900 text-start whitespace-nowrap"
                                    >
                                        Satuan
                                    </TableCell>

                                    <TableCell
                                        isHeader
                                        className="px-6 py-3 text-sm font-semibold text-gray-900 text-start whitespace-nowrap"
                                    >
                                        Koefisien
                                    </TableCell>

                                    <TableCell
                                        isHeader
                                        className="px-6 py-3 text-sm font-semibold text-gray-900 text-start whitespace-nowrap"
                                    >
                                        Vol x Koef.
                                    </TableCell>

                                    <TableCell
                                        isHeader
                                        className="px-6 py-3 text-sm font-semibold text-gray-900 text-start whitespace-nowrap"
                                    >
                                        Harga
                                    </TableCell>

                                    <TableCell
                                        isHeader
                                        className="px-6 py-3 text-sm font-semibold text-gray-900 text-start whitespace-nowrap"
                                    >
                                        Jumlah
                                    </TableCell>
                                </TableRow>
                            </TableHeader>

                            {/* Body */}
                            {tools.map((item, index) => {
                                return (
                                    <TableBody key={index} className="divide-y divide-gray-100 border-b">
                                        <TableRow className="hover:bg-gray-50 transition">
                                            {/* Name */}
                                            <TableCell className="px-6 py-4 text-sm font-medium text-start text-gray-800 whitespace-nowrap">
                                                {capitalizeEachWord(item.name)}
                                            </TableCell>

                                            {/* Unit */}
                                            <TableCell className="px-6 py-4 text-sm font-medium text-start text-gray-800 whitespace-nowrap">
                                                {item.unit}
                                            </TableCell>

                                            {/* Coefficient */}
                                            <TableCell className="px-6 py-4 text-sm font-medium text-start text-gray-800 whitespace-nowrap">
                                                {formatDecimal(item.coefficient)}
                                            </TableCell>

                                            {/* Vol x Koef. */}
                                            <TableCell className="px-6 py-4 text-sm font-medium text-start text-gray-800 whitespace-nowrap">
                                                {formatDecimal(item.qty)}
                                            </TableCell>

                                            {/* Price */}
                                            <TableCell className="px-6 py-4 text-sm font-medium text-start text-gray-800 whitespace-nowrap">
                                                {formatCurrency(item.price)}
                                            </TableCell>

                                            {/* Total */}
                                            <TableCell className="px-6 py-4 text-sm font-medium text-start text-gray-800 whitespace-nowrap">
                                                {formatCurrency(item.total)}
                                            </TableCell>
                                        </TableRow>
                                    </TableBody>
                                );
                            })}
                        </Table>
                    </div>
                </div>
            )}
        </div>
    )
}

export default ToolTable;
