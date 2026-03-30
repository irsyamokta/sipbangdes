type ActionType = "send" | "revision" | "forward" | "approve";

const confirmConfig: Record<ActionType, { title: string; text: string }> = {
    send: {
        title: "Kirim RAB?",
        text: "RAB akan dikirim ke Sekretaris Desa",
    },
    forward: {
        title: "Teruskan RAB?",
        text: "RAB akan diteruskan ke Kepala Desa",
    },
    approve: {
        title: "Setujui RAB?",
        text: "RAB yang sudah disetujui tidak dapat diubah",
    },
    revision: {
        title: "",
        text: "",
    },
};

const successMessage: Record<ActionType, string> = {
    send: "RAB berhasil dikirim",
    forward: "RAB berhasil diteruskan",
    approve: "RAB berhasil disetujui",
    revision: "Revisi berhasil dikirim",
};

export { confirmConfig, successMessage };
