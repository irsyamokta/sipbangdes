export const capitalizedFirst = (str: string) =>
    str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();

export const capitalizeEachWord = (str: string): string =>
    str
        .toLowerCase()
        .split(" ")
        .filter(Boolean)
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(" ");
