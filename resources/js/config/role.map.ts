import type { MenuKey } from "./MenuRegistry";

export type Role = "admin" | "planner" | "reviewer" | "approver";

export const ROLE_MENU: Record<
    Role,
    Record<string, MenuKey[]>
> = {
    admin: {
        main: ["dashboard", "proyek", "tos", "rab"],
        master: ["material", "alat", "upah", "satuan", "kategori", "ahsp"],
        admin: ["pengguna"],
    },
    planner: {
        main: ["dashboard", "proyek", "tos", "rab", "ahsp", "kategori"],
    },
    reviewer: {
        main: ["dashboard", "proyek", "tos", "rab", "ahsp", "kategori"],
    },
    approver: {
        main: ["dashboard", "proyek", "tos", "rab", "ahsp", "kategori"],
    },
};
