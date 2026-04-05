import { Head } from '@inertiajs/react';
import ImageNotFound from "../../../assets/svg/img-404.svg";

export default function NotFound() {
    return (
        <>
            <Head title="Not Found" />

            <div className="flex flex-col items-center justify-center min-h-screen bg-gray-100">
                <img src={ImageNotFound} alt="" className="w-72 h-auto" />
                <p className="mt-4 text-xl text-center font-semibold">Halaman Tidak Ditemukan</p>
            </div>
        </>
    );
}
