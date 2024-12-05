// resources/js/Pages/Profile/Partials/UpdatePasswordForm.tsx
import { useForm } from '@inertiajs/react';
import { FormEventHandler, useRef } from 'react';
import { Input } from "@/Components/ui/input"
import { Button } from "@/Components/ui/button"
import { Lock, Check } from 'lucide-react';

export default function UpdatePasswordForm({
    className = '',
}: {
    className?: string;
}) {
    const passwordInput = useRef<HTMLInputElement>(null);
    const currentPasswordInput = useRef<HTMLInputElement>(null);

    const { data, setData, errors, put, reset, processing, recentlySuccessful } = useForm({
        current_password: '',
        password: '',
        password_confirmation: '',
    });

    const updatePassword: FormEventHandler = (e) => {
        e.preventDefault();

        put(route('password.update'), {
            preserveScroll: true,
            onSuccess: () => reset(),
            onError: (errors) => {
                if (errors.password) {
                    reset('password', 'password_confirmation');
                    passwordInput.current?.focus();
                }
                if (errors.current_password) {
                    reset('current_password');
                    currentPasswordInput.current?.focus();
                }
            },
        });
    };

    return (
        <section className={className}>
            <header>
                <h2 className="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Ubah Password
                </h2>
                <p className="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Pastikan akun Anda menggunakan password yang panjang dan acak untuk tetap aman.
                </p>
            </header>

            <form onSubmit={updatePassword} className="mt-6 space-y-6">
                <div className="space-y-2">
                    <label className="text-sm font-medium">Password Saat Ini</label>
                    <div className="relative">
                        <Lock className="absolute left-3 top-3 h-4 w-4 text-gray-500" />
                        <Input
                            type="password"
                            ref={currentPasswordInput}
                            value={data.current_password}
                            onChange={e => setData('current_password', e.target.value)}
                            className="pl-9"
                        />
                    </div>
                    {errors.current_password && <p className="text-sm text-red-600">{errors.current_password}</p>}
                </div>

                <div className="space-y-2">
                    <label className="text-sm font-medium">Password Baru</label>
                    <div className="relative">
                        <Lock className="absolute left-3 top-3 h-4 w-4 text-gray-500" />
                        <Input
                            type="password"
                            ref={passwordInput}
                            value={data.password}
                            onChange={e => setData('password', e.target.value)}
                            className="pl-9"
                        />
                    </div>
                    {errors.password && <p className="text-sm text-red-600">{errors.password}</p>}
                </div>

                <div className="space-y-2">
                    <label className="text-sm font-medium">Konfirmasi Password Baru</label>
                    <div className="relative">
                        <Lock className="absolute left-3 top-3 h-4 w-4 text-gray-500" />
                        <Input
                            type="password"
                            value={data.password_confirmation}
                            onChange={e => setData('password_confirmation', e.target.value)}
                            className="pl-9"
                        />
                    </div>
                    {errors.password_confirmation && <p className="text-sm text-red-600">{errors.password_confirmation}</p>}
                </div>

                <div className="flex items-center gap-4">
                    <Button type="submit" disabled={processing}>
                        {processing ? 'Menyimpan...' : 'Simpan'}
                    </Button>

                    {recentlySuccessful && (
                        <span className="flex items-center text-sm text-green-600">
                            <Check className="mr-1 h-4 w-4" />
                            Tersimpan
                        </span>
                    )}
                </div>
            </form>
        </section>
    );
}