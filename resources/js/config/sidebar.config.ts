import { MENU } from "./MenuRegistry";
import { ROLE_MENU } from "./role.map";

type Role = keyof typeof ROLE_MENU;

export const getSidebarConfig = (role: Role) => {
    const roleConfig = ROLE_MENU[role];

    return Object.entries(roleConfig).map(([section, keys]) => ({
        title:
            section === "main"
                ? "Menu Utama"
                : section === "master"
                    ? "Master Data"
                    : "Administrasi",
        items: keys.map((key) => MENU[key]),
    }));
};
