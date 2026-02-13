import { TableCell, TableRow } from "@/Components/ui/table";

interface EmptyTableProps {
    description?: string
    colspan?: number
}
export function EmptyTable(colspan: EmptyTableProps) {
    return (
        <TableRow>
            <TableCell colSpan={colspan.colspan} className="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                {colspan.description}
            </TableCell>
        </TableRow>
    );
}
