import { useState } from 'react';
import { PageProps } from '@/types';
import { Head, Link } from '@inertiajs/react';
import Input from '@/Components/form/input/InputField';
import FileInput from '@/Components/form/input/FileInput';
import { AiOutlineMail } from "react-icons/ai";
import { TbLockPassword } from "react-icons/tb";
import CurrencyInput from '@/Components/form/input/CurrencyInput';
import Checkbox from '@/Components/form/input/Checkbox';
import Radio from '@/Components/form/input/Radio';
import TextArea from '@/Components/form/input/TextArea';
import Select from '@/Components/form/input/Select';
import MultiSelect from '@/Components/form/input/MultiSelect';
import TimeSelect from '@/Components/form/input/TimeSelect';
import DatePicker from '@/Components/form/input/DatePicker';
import Switch from '@/Components/form/input/Switch';

export default function Welcome({
    auth,
    laravelVersion,
    phpVersion,
}: PageProps<{ laravelVersion: string; phpVersion: string }>) {
    const handleImageError = () => {
        document
            .getElementById('screenshot-container')
            ?.classList.add('!hidden');
        document.getElementById('docs-card')?.classList.add('!row-span-1');
        document
            .getElementById('docs-card-content')
            ?.classList.add('!flex-row');
        document.getElementById('background')?.classList.add('!hidden');
    };

    const setAmount = (value: number | null) => {
        setAmountInternal(value ?? 0);
    };

    const [amountInternal, setAmountInternal] = useState<number>(0);

    const [agree, setAgree] = useState(false);
    const [gender, setGender] = useState("male");
    const [description, setDescription] = useState("");
    const [role, setRole] = useState("");
    const [selectedOptions, setSelectedOptions] = useState<string[]>([]);
    const [time, setTime] = useState("");
    const [date, setDate] = useState("");
    const [files, setFiles] = useState<File[]>([]);
    const [isSwitched, setIsSwitched] = useState(true);

    return (
        <>
            <Head title="Welcome" />
            <div className="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50 py-16">
                <div className='flex px-8 gap-2 py-4'>
                    <Input
                        label="Email"
                        type="text"
                        startIcon={<AiOutlineMail />}
                        placeholder="Email"
                        required
                    />
                    <Input
                        label="Password"
                        type="password"
                        startIcon={<TbLockPassword />}
                        placeholder="Password"
                        enablePasswordValidation={false}
                        required
                    />
                    <FileInput
                        label="File"
                        variant="card"
                        required
                        multiple
                    />
                </div>
                <div className='flex px-8 gap-2 py-4'>
                    <CurrencyInput
                        label="Total Anggaran"
                        required
                        value={amountInternal}
                        onChange={setAmount}
                        placeholder="Masukkan nominal"
                        isCurrency
                    />
                    <Checkbox
                        label="Setuju"
                        checked={agree}
                        onChange={setAgree}
                        required
                    />
                    <div className="space-y-3">
                        <Radio
                            name="gender"
                            value="male"
                            label="Laki-laki"
                            checked={gender === "male"}
                            onChange={setGender}
                            required
                        />

                        <Radio
                            name="gender"
                            value="female"
                            label="Perempuan"
                            checked={gender === "female"}
                            onChange={setGender}
                        />
                    </div>
                    <TextArea
                        label="Deskripsi"
                        value={description}
                        onChange={setDescription}
                        required
                        hint="Maksimal 255 karakter"
                        maxLength={255}
                    />
                </div>
                <div className='flex px-8 gap-2 py-4'>
                    <Select
                        label="Role"
                        value={role}
                        onChange={setRole}
                        required
                        options={[
                            { value: "admin", label: "Admin" },
                            { value: "planner", label: "Kaur Perencanaan" },
                            { value: "reviewer", label: "Sekretaris Desa" },
                            { value: "approval", label: "Kepala Desa" },
                        ]}
                    />
                    <MultiSelect
                        label="Roles"
                        value={selectedOptions}
                        onChange={setSelectedOptions}
                        required
                        options={[
                            { value: "admin", label: "Admin" },
                            { value: "planner", label: "Planner" },
                            { value: "reviewer", label: "Reviewer" },
                        ]}
                    />
                    <TimeSelect
                        label="Waktu"
                        value={time}
                        onChange={setTime}
                        required
                    />
                </div>
                <div className='flex px-8 gap-2 py-4 w-1/2'>
                    <DatePicker
                        name="start_date"
                        label="Start Date"
                        value={date}
                        onChange={setDate}
                        required
                        hint="Pilih tanggal mulai"
                    />
                    <Switch
                        label="Mode Gelap"
                        defaultChecked={isSwitched}
                        onChange={setIsSwitched}
                    />
                </div>
            </div>
        </>
    );
}
