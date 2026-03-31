const rabStatusMap: Record<
    string,
    { label: string; color: "success" | "warning" | "error" | "info" | "light" }
> = {
    send: {
        label: "Dikirim",
        color: "info",
    },
    forward: {
        label: "Diteruskan",
        color: "info",
    },
    revision: {
        label: "Revisi",
        color: "warning",
    },
    approved: {
        label: "Disetujui",
        color: "success",
    },
    draft: {
        label: "Draft",
        color: "light",
    },
    submitted: {
        label: "Dikirim",
        color: "info",
    },
    reviewed: {
        label: "Ditinjau",
        color: "info",
    },
};

export default rabStatusMap;
