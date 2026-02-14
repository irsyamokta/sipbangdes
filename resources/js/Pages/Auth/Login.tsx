import { Head, Link, useForm } from "@inertiajs/react";
import { toast } from "sonner";

import Form from "@/Components/form/Form";
import Input from "@/Components/form/input/InputField";
import Button from "@/Components/ui/button/Button";

import { AiOutlineMail } from "react-icons/ai";
import { TbLockPassword } from "react-icons/tb";

import LogoColor from "../../../assets/logo/logo-color.svg"
import Illustration from "../../../assets/svg/image-login.svg"

export default function Login({
    status,
}: {
    status?: string;
}) {
    const { data, setData, post, processing, errors, reset } = useForm({
        email: "",
        password: "",
    });

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        post(route("login"), {
            onFinish: () => {
                reset("password");
            },
            onSuccess: () => {
                toast.success("Login Berhasil");
            },
            onError: () => {
                toast.error("Login Gagal");
            },
        });
    };

    return (
        <>
            <Head title="Login" />

            <div className="min-h-screen flex items-center justify-center">
                {/* Left Side */}
                <div className="w-full lg:w-1/2 h-screen flex flex-col justify-center px-6 md:px-10 lg:px-20 bg-white">
                    {/* Logo */}
                    <div className="flex items-center gap-3 mb-10">
                        <img
                            src={LogoColor}
                            alt="Logo SIPBANGDES"
                        />
                    </div>

                    {/* Title */}
                    <h1 className="text-3xl font-bold text-gray-900 mb-2">
                        Masuk Akun Anda
                    </h1>
                    <p className="text-gray-500 mb-10">
                        Selamat Datang Kembali!
                    </p>

                    {/* Status */}
                    {status && (
                        <div className="mb-4 text-sm font-medium text-green-600">
                            {status}
                        </div>
                    )}

                    {/* Form */}
                    <Form onSubmit={submit} className="space-y-6">
                        {/* Email */}
                        <Input
                            label="Email"
                            id="email"
                            type="email"
                            name="email"
                            value={data.email}
                            startIcon={<AiOutlineMail />}
                            placeholder="Email"
                            autoComplete="username"
                            onChange={(e) =>
                                setData("email", e.target.value)
                            }
                            error={errors.email}
                        />

                        {/* Password */}
                        <Input
                            label="Password"
                            id="password"
                            type="password"
                            name="password"
                            value={data.password}
                            startIcon={<TbLockPassword />}
                            placeholder="Password"
                            autoComplete="current-password"
                            onChange={(e) =>
                                setData("password", e.target.value)
                            }
                            enablePasswordValidation={false}
                            error={errors.password}
                        />

                        {/* Button */}
                        <Button
                            type="submit"
                            size="lg"
                            className="w-full"
                            disabled={processing}
                        >
                            Masuk
                        </Button>

                        {/* Forgot Password */}
                        <div className="text-center text-sm text-gray-500 mt-4">
                            Lupa Password?{" "}
                            <Link
                                href={"https://wa.me/6281286966966"}
                                target="_blank"
                                className="text-primary font-semibold hover:underline"
                            >
                                Hubungi Admin
                            </Link>
                        </div>
                    </Form>
                </div>

                {/* Right Side */}
                <div className="w-1/2 h-screen py-3 px-3 hidden lg:flex">
                    <div className="bg-primary flex flex-col justify-center items-center rounded-xl p-10">
                        {/* Illustration */}
                        <img
                            src={Illustration}
                            alt="Login Illustration"
                            className="w-[80%] mb-10"
                        />

                        {/* Description */}
                        <p className="text-center text-white text-sm max-w-md leading-relaxed">
                            Setiap tahapan pembangunan desa direncanakan secara
                            terukur, dilaksanakan secara efisien, dan
                            dipertanggungjawabkan untuk memastikan manfaat yang
                            maksimal bagi masyarakat.
                        </p>
                    </div>
                </div>
            </div>
        </>
    );
}
