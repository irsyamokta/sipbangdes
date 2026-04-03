import { useState } from "react";

import { RABTabsProps } from "@/types/rab";

import Tabs from "@/Components/ui/tabs/Tabs";
import EmptyState from "@/Components/empty/EmptyState";
import SummaryCard from "@/Components/card/SummaryCard";
import { RabDetailAccordion } from "../accordion/RabDetailAccordion";
import { RecapMaterialTable } from "../table/RecapMaterialTable";
import { MaterialTable } from "../table/MaterialTable";
import { RecapWageTable } from "../table/RecapWageTable";
import { WageTable } from "../table/WageTable";
import { RecapToolTable } from "../table/RecapToolTable";
import { ToolTable } from "../table/ToolTable";
import { OperationalTable } from "../table/OperationalTable";
import { AIInsightCard } from "../card/AIInsightCard";

export default function RABTabs({
    project_id,
    rab_status,
    detail,
    recapMaterial,
    recapWage,
    recapTool,
    operational,
    unitOptions
}: RABTabsProps) {
    const [openId, setOpenId] = useState<string | null>(null);

    const toggle = (id: string) => {
        setOpenId((prev) => (prev === id ? null : id));
    };

    const tabs = [
        {
            key: "detail",
            label: "Detail RAB",
            content: (
                <div className="flex flex-col gap-4 mt-4">
                    {detail.length === 0 ? (
                        <div className="mt-4">
                            <EmptyState
                                title="Tidak Ada Detail Pekerjaan"
                                description="Belum ada detail pekerjaan yang tersedia"
                            />
                        </div>
                    ) : (
                        detail.map((item, index) => (
                            <RabDetailAccordion
                                key={index}
                                id={item.id}
                                work_code={item.work_code}
                                work_name={item.work_name}
                                category={item.category}
                                unit={item.unit}
                                volume={item.volume}
                                subtotal={item.subtotal}
                                open={openId === item.id}
                                toggle={() => toggle(item.id)}
                            >
                                {/* Summary Card*/}
                                <SummaryCard
                                    material_total={item.material_total}
                                    wage_total={item.wage_total}
                                    tool_total={item.tool_total}
                                />

                                {/* Material */}
                                <MaterialTable materials={item.materials} />

                                {/* Wage */}
                                <WageTable wages={item.wages} />

                                {/* Tool */}
                                <ToolTable tools={item.tools} />
                            </RabDetailAccordion>
                        ))
                    )}
                </div>
            ),
        },
        {
            key: "material",
            label: "Rekap Material",
            content: <RecapMaterialTable materials={recapMaterial} />,
        },
        {
            key: "upah",
            label: "Rekap Upah",
            content: <RecapWageTable wages={recapWage} />,
        },
        {
            key: "alat",
            label: "Rekap Alat",
            content: <RecapToolTable tools={recapTool} />,
        },
        {
            key: "operasional",
            label: "Biaya Operasional",
            content: <OperationalTable project_id={project_id} rab_status={rab_status} operationals={operational} unitOptions={unitOptions}/>,
        },
        {
            key: "insight",
            label: "Insight",
            content: <AIInsightCard />,
        },
    ];

    return (
        <Tabs
            tabs={tabs}
            storageKey="rab-tabs"
            defaultTab="detail"
        />
    );
}
