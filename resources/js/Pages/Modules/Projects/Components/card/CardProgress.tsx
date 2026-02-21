import { ProjectProgressCardProps } from "@/types/progress";

import { IoIosTrendingUp } from "react-icons/io";

export const CardProgress = ({
    project,
    totalProgress
}: ProjectProgressCardProps) => {
    return (
        <div className="rounded-2xl border border-gray-200 bg-white p-6 transition hover:shadow-md">
            {/* Header */}
            <h1 className="flex items-center gap-2 text-xl md:text-2xl font-semibold">
                <span><IoIosTrendingUp /></span>
                {project.project_name}
            </h1>
            <p className="text-sm sm:text-base text-gray-500 mt-1">
                Progres pelaksanaan proyek saat ini
            </p>

            {/* Progress */}
            <div className="mt-6">
                <div className="flex justify-between text-sm mb-1">
                    <span className="text-gray-500">Progres</span>
                    <span>{totalProgress}%</span>
                </div>

                <div className="w-full bg-gray-200 rounded-full h-2 md:h-2.5">
                    <div
                        className="bg-primary h-2 md:h-2.5 rounded-full transition-all"
                        style={{ width: `${totalProgress}%` }}
                    />
                </div>
            </div>
        </div>
    )
}
