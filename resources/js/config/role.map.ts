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
            "kategori",
            "ahsp",
            "tos",
            "rab",
        ],

        master: [
            "material",
            "alat",
            "upah",
            "satuan",
        ],

        admin: ["pengguna"],
    },

    planner: {
        main: [
            "dashboard",
            "proyek",
            "kategori",
            "ahsp",
            "tos",
            "rab",
        ],
    },

    reviewer: {
        main: [
            "dashboard",
            "proyek",
            "kategori",
            "ahsp",
            "tos",
            "rab",
        ],
    },

    approver: {
        main: [
            "dashboard",
            "proyek",
            "kategori",
            "ahsp",
            "tos",
            "rab",
        ],
    },
};
