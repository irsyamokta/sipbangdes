import { ReactNode } from "react";
import {
    LuLayoutDashboard,
    LuUsers,
    LuWallet,
} from "react-icons/lu";
import { AiOutlineProject } from "react-icons/ai";
import { LiaClipboardListSolid, LiaToolsSolid } from "react-icons/lia";
import { PiNote, PiPackage, PiCalculatorLight } from "react-icons/pi";
import { TfiRulerAlt } from "react-icons/tfi";
import { GrUserWorker } from "react-icons/gr";

export interface MenuItem {
    name: string;
    icon: ReactNode;
    path: string;
}

export const MENU = {
    dashboard: {
        name: "Dashboard",
        icon: <LuLayoutDashboard size={20} />,
        path: "/dashboard",
    },
    proyek: {
        name: "Proyek",
        icon: <AiOutlineProject size={20} />,
        path: "/proyek",
    },
    tos: {
        name: "Take Off Sheet",
        icon: <LiaClipboardListSolid size={20} strokeWidth={0.5} />,
        path: "/take-off-sheet",
    },
    rab: {
        name: "RAB",
        icon: <PiNote size={20} />,
        path: "/rab",
    },
    material: {
        name: "Material",
        icon: <PiPackage size={20} />,
        path: "/material",
    },
    alat: {
        name: "Alat",
        icon: <LiaToolsSolid size={20} />,
        path: "/alat",
    },
    upah: {
        name: "Upah",
        icon: <LuWallet size={20} />,
        path: "/upah",
    },
    satuan: {
        name: "Satuan",
        icon: <TfiRulerAlt size={20} />,
        path: "/satuan",
    },
    kategori: {
        name: "Kategori Pekerjaan",
        icon: <GrUserWorker size={20} />,
        path: "/kategori-pekerjaan",
    },
    ahsp: {
        name: "AHSP",
        icon: <PiCalculatorLight size={20} />,
        path: "/ahsp",
    },
    pengguna: {
        name: "Pengguna",
        icon: <LuUsers size={20} />,
        path: "/pengguna",
    },
} as const satisfies Record<string, MenuItem>;

export type MenuKey = keyof typeof MENU;
