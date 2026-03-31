import { WageTableProps } from "@/types/rab";

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

import { LuWallet } from "react-icons/lu";

export const WageTable = ({
    wages
}: WageTableProps) => {

    return (
        <div className="flex flex-col gap-4 py-2 mt-4">
            <div className="flex gap-2 justify-between items-center">
                <div className="flex gap-2 items-center">
                    <LuWallet size={20} className="text-warning-700" />
                    <p className="font-semibold">Upah</p>
                </div>
            </div>
            {wages.length === 0 ? (
                <p className="text-gray-500 -mt-1">Belum ada komponen upah</p>
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
                                        Jabatan
                                    </TableCell>

                                    <TableCell
                                        isHeader
                                        className="px-6 py-3 text-sm font-semibold text-gray-900 text-end whitespace-nowrap"
                                    >
                                        Satuan
                                    </TableCell>

                                    <TableCell
                                        isHeader
                                        className="px-6 py-3 text-sm font-semibold text-gray-900 text-end whitespace-nowrap"
                                    >
                                        Koefisien
                                    </TableCell>

                                    <TableCell
                                        isHeader
                                        className="px-6 py-3 text-sm font-semibold text-gray-900 text-end whitespace-nowrap"
                                    >
                                        Vol x Koef.
                                    </TableCell>

                                    <TableCell
                                        isHeader
                                        className="px-6 py-3 text-sm font-semibold text-gray-900 text-end whitespace-nowrap"
                                    >
                                        Harga
                                    </TableCell>

                                    <TableCell
                                        isHeader
                                        className="px-6 py-3 text-sm font-semibold text-gray-900 text-end whitespace-nowrap"
                                    >
                                        Jumlah
                                    </TableCell>
                                </TableRow>
                            </TableHeader>

                            {/* Body */}
                            {wages.map((item, index) => {
                                return (
                                    <TableBody key={index} className="divide-y divide-gray-100 border-b">
                                        <TableRow className="hover:bg-gray-50 transition">
                                            {/* Name */}
                                            <TableCell className="px-6 py-4 text-sm font-medium text-gray-800 whitespace-nowrap">
                                                {capitalizeEachWord(item.name)}
                                            </TableCell>

                                            {/* Unit */}
                                            <TableCell className="px-6 py-4 text-sm font-medium text-end text-gray-800 whitespace-nowrap">
                                                {item.unit}
                                            </TableCell>

                                            {/* Koefisien */}
                                            <TableCell className="px-6 py-4 text-sm font-medium text-end text-gray-800 whitespace-nowrap">
                                                {formatDecimal(item.coefficient)}
                                            </TableCell>

                                            {/* Vol x Koef. */}
                                            <TableCell className="px-6 py-4 text-sm font-medium text-end text-gray-800 whitespace-nowrap">
                                                {formatDecimal(item.qty)}
                                            </TableCell>

                                            {/* Price */}
                                            <TableCell className="px-6 py-4 text-sm font-medium text-end text-gray-800 whitespace-nowrap">
                                                {formatCurrency(item.price)}
                                            </TableCell>

                                            {/* Total */}
                                            <TableCell className="px-6 py-4 text-sm font-medium text-end text-gray-800 whitespace-nowrap">
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
