export function formatDate(date: Date | string): string {
    const dateObj = new Date(date);
    return dateObj.toISOString().split("T")[0];
};

export function formatTime(date: Date | string): string {
    const dateObj = new Date(`1970-01-01T${date}`);
    return dateObj.toLocaleTimeString("id-ID", {
        hour: "2-digit",
        minute: "2-digit",
        hour12: false,
    });
}

export function formatCalendarDate(date: Date | string): string {
    const dateObj = new Date(date);

    if (isNaN(dateObj.getTime())) {
        return 'Tanggal tidak valid';
    }

    const options: Intl.DateTimeFormatOptions = {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
    };

    return new Intl.DateTimeFormat('id-ID', options).format(dateObj);
};

export const formatDateTime = (date: string | Date): string => {
    const parsedDate = new Date(date);

    if (isNaN(parsedDate.getTime())) {
        return 'Invalid Date';
    }

    const dateOptions: Intl.DateTimeFormatOptions = {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    };

    const timeOptions: Intl.DateTimeFormatOptions = {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: false,
    };

    const formatterDate = new Intl.DateTimeFormat('en-GB', dateOptions).format(parsedDate);
    const formatterTime = new Intl.DateTimeFormat('en-GB', { ...timeOptions, timeZone: 'Asia/Jakarta' }).format(parsedDate);

    return `${formatterDate} - ${formatterTime}`;
};
