import type { MenuKey } from "./MenuRegistry";

export type Role = "admin" | "planner" | "reviewer" | "approver";

export const ROLE_MENU: Record<
    Role,
    Record<string, MenuKey[]>
> = {
     admin: {
        main: [
            "dashboard",
            "proyek",
            "ahsp",
            "kategori",
            "tos",
            "rab",
        ],

        master: [
            "satuan",
            "material",
            "upah",
            "alat",
        ],

        admin: ["pengguna"],
    },

    planner: {
        main: [
            "dashboard",
            "proyek",
            "ahsp",
            "kategori",
            "tos",
            "rab",
        ],
    },

    reviewer: {
        main: [
            "dashboard",
            "proyek",
            "ahsp",
            "kategori",
            "tos",
            "rab",
        ],
    },

    approver: {
        main: [
            "dashboard",
            "proyek",
            "ahsp",
            "kategori",
            "tos",
            "rab",
        ],
    },
};
