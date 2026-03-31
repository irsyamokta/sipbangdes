import { PageProps } from ".";

interface DashboardSummary {
    total_project: number;
    active_project: number;
    total_ahsp: number;
    total_tos: number;
}

interface RabPerYear {
    year: number;
    total_rab: number;
    total_project: number;
}

interface LatestProject {
    id: string;
    project_name: string;
    location: string;
    budget_year: number;
    total_items: number;
    status: string;
    subtotal: number;
}

interface TopCategory {
    name: string;
    total_items: number;
}

interface DashboardData {
    summary: DashboardSummary;
    rab_per_year: RabPerYear[];
    latest_projects: LatestProject[];
    top_categories: TopCategory[];
}

interface DashboardPageProps extends PageProps {
    data: DashboardData;
}

export type {
    DashboardSummary,
    RabPerYear,
    LatestProject,
    TopCategory,
    DashboardData,
    DashboardPageProps,
};
