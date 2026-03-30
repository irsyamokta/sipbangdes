import React, { useEffect, useState } from "react";

type TabItem = {
    key: string;
    label: string;
    content: React.ReactNode;
};

type TabsProps = {
    tabs: TabItem[];
    storageKey?: string;
    defaultTab?: string;
};

const Tabs: React.FC<TabsProps> = ({
    tabs,
    storageKey = "active-tab",
    defaultTab,
}) => {
    const [activeTab, setActiveTab] = useState<string>("");

    useEffect(() => {
        const savedTab = localStorage.getItem(storageKey);
        if (savedTab && tabs.find((t) => t.key === savedTab)) {
            setActiveTab(savedTab);
        } else {
            setActiveTab(defaultTab || tabs[0].key);
        }
    }, [tabs, storageKey, defaultTab]);

    useEffect(() => {
        if (activeTab) {
            localStorage.setItem(storageKey, activeTab);
        }
    }, [activeTab, storageKey]);

    return (
        <div className="w-full">
            {/* Tab Header */}
            <div className="w-full overflow-x-auto">
                <div className="inline-flex bg-gray-200 rounded-lg p-1 gap-1 min-w-max">
                    {tabs.map((tab) => {
                        const isActive = activeTab === tab.key;

                        return (
                            <button
                                key={tab.key}
                                onClick={() => setActiveTab(tab.key)}
                                className={`whitespace-nowrap px-4 py-2 text-sm font-medium rounded-lg transition
                                    ${
                                        isActive
                                            ? "bg-white shadow text-gray-900"
                                            : "text-gray-500 hover:text-gray-700"
                                    }`}
                            >
                                {tab.label}
                            </button>
                        );
                    })}
                </div>
            </div>

            {/* Tab Content */}
            <div className="mt-4">
                {tabs.find((tab) => tab.key === activeTab)?.content}
            </div>
        </div>
    );
};

export default Tabs;
