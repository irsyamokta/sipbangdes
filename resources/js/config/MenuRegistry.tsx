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
        icon: <LuLayoutDashboard size={24} />,
        path: "/dashboard",
    },
    proyek: {
        name: "Proyek",
        icon: <AiOutlineProject size={24} />,
        path: "/proyek",
    },
    tos: {
        name: "Take Off Sheet",
        icon: <LiaClipboardListSolid size={24} strokeWidth={0.5} />,
        path: "/take-off-sheet",
    },
    rab: {
        name: "RAB",
        icon: <PiNote size={24} />,
        path: "/rab",
    },
    material: {
        name: "Material",
        icon: <PiPackage size={24} />,
        path: "/material",
    },
    alat: {
        name: "Alat",
        icon: <LiaToolsSolid size={24} />,
        path: "/alat",
    },
    upah: {
        name: "Upah",
        icon: <LuWallet size={24} />,
        path: "/upah",
    },
    satuan: {
        name: "Satuan",
        icon: <TfiRulerAlt size={24} />,
        path: "/satuan",
    },
    kategori: {
        name: "Kategori Pekerjaan",
        icon: <GrUserWorker size={24} />,
        path: "/kategori-pekerjaan",
    },
    ahsp: {
        name: "AHSP",
        icon: <PiCalculatorLight size={24} />,
        path: "/ahsp",
    },
    pengguna: {
        name: "Pengguna",
        icon: <LuUsers size={24} />,
        path: "/pengguna",
    },
} as const satisfies Record<string, MenuItem>;

export type MenuKey = keyof typeof MENU;
