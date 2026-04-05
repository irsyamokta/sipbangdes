import { Head } from '@inertiajs/react';
import ImageError from "../../../assets/svg/img-500.svg";

import { usePage } from '@inertiajs/react';

export default function InternalError() {
    const { message } = usePage().props as any;

    return (
        <>
            <Head title="Error" />

            <div className="flex flex-col items-center justify-center min-h-screen bg-gray-100">
                <img src={ImageError} alt="" className="w-72 h-auto" />
                <p className="mt-4 text-xl text-center font-semibold px-24">{message}</p>
            </div>
        </>
    );
}
