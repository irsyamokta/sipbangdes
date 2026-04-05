import { RabWageRecap } from "@/types/rab";

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
import { capitalizeEachWord } from "@/utils/capitalize";

import { LuWallet } from "react-icons/lu";

interface RecapWagesTableProps {
    wages: RabWageRecap[];
}

const RecapWageTable = ({
    wages
}: RecapWagesTableProps) => {
    const totalWage = wages.reduce(
        (sum, item) => sum + item.total,
        0
    );

    return (
        <div className="overflow-hidden rounded-xl bg-white border border-gray-300">

            {/* Header */}
            <div className="flex gap-2 items-center bg-warning-50 px-4 py-3 border-b border-gray-300">
                <LuWallet size={20} className="text-warning-600" />
                <p className="font-semibold text-gray-800">
                    Rekap Kebutuhan Upah
                </p>
            </div>

            {/* Table */}
            <div className="max-w-full overflow-x-auto">
                <Table>
                    {/* Header */}
                    <TableHeader className="bg-gray-200 border-b">
                        <TableRow>
                            <TableCell isHeader className="px-6 py-3 text-xs text-start text-gray-900 whitespace-nowrap">
                                Jabatan
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
                        {wages.length === 0 ? (
                            <EmptyTable
                                colspan={5}
                                description="Belum ada data upah"
                            />
                        ) : (
                            wages.map((item, index) => (
                                <TableRow
                                    key={`${item.name}-${index}`}
                                    className="hover:bg-gray-50 transition"
                                >
                                    {/* Name */}
                                    <TableCell className="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                                        {capitalizeEachWord(item.name)}
                                    </TableCell>

                                    {/* Unit */}
                                    <TableCell className="px-6 py-4 text-sm text-end text-gray-900">
                                        {item.unit}
                                    </TableCell>

                                    {/* Quantity */}
                                    <TableCell className="px-6 py-4 text-sm text-end text-gray-900 whitespace-nowrap">
                                        {formatDecimal(item.quantity)}
                                    </TableCell>

                                    {/* Price */}
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
            {wages.length > 0 && (
                <div className="flex justify-end items-center gap-4 px-6 py-3 bg-warning-50 border-t border-gray-300">
                    <p className="text-sm font-semibold text-gray-800">
                        Total Upah
                    </p>
                    <p className="text-sm font-bold text-warning-600">
                        {formatCurrency(totalWage)}
                    </p>
                </div>
            )}
        </div>
    );
};

export default RecapWageTable;
