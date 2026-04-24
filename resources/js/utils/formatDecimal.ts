export function formatDecimal(
    value: number,
    minFractionDigits = 0,
    maxFractionDigits = 8
) {
    return new Intl.NumberFormat("id-ID", {
        minimumFractionDigits: minFractionDigits,
        maximumFractionDigits: maxFractionDigits,
    }).format(value);
}
