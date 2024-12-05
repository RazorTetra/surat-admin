// resources/js/Pages/Auth/ConfirmPassword.tsx
import { Head, useForm } from '@inertiajs/react';
import { FormEventHandler } from 'react';
import { Card, CardContent, CardHeader, CardTitle } from "@/Components/ui/card"
import { Input } from "@/Components/ui/input"
import { Button } from "@/Components/ui/button"
import { Lock, ShieldAlert } from 'lucide-react';

export default function ConfirmPassword() {
    const { data, setData, post, processing, errors, reset } = useForm({
        password: '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('password.confirm'), {
            onFinish: () => reset('password'),
        });
    };

    return (
        <div className="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 py-16 dark:from-gray-900 dark:to-gray-800">
            <Head title="Konfirmasi Password - SURAT PTIK" />
            <div className="container mx-auto flex flex-col items-center px-6">
                <div className="mb-8 flex items-center space-x-2">
                    <ShieldAlert className="h-8 w-8 text-blue-600" />
                    <span className="text-2xl font-bold text-gray-900 dark:text-white">SURAT PTIK</span>
                </div>
                <Card className="w-full max-w-md">
                    <CardHeader>
                        <CardTitle className="text-center">Konfirmasi Password</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div className="mb-4 rounded-lg bg-blue-50 p-4 text-sm text-gray-600 dark:bg-blue-900/50 dark:text-gray-300">
                            Ini adalah area yang aman dari aplikasi. Mohon konfirmasi password Anda sebelum melanjutkan.
                        </div>

                        <form onSubmit={submit} className="space-y-4">
                            <div className="space-y-2">
                                <label className="text-sm font-medium">Password</label>
                                <div className="relative">
                                    <Lock className="absolute left-3 top-3 h-4 w-4 text-gray-500" />
                                    <Input type="password" value={data.password} className="pl-9" onChange={e => setData('password', e.target.value)} />
                                </div>
                                {errors.password && <p className="text-sm text-red-600">{errors.password}</p>}
                            </div>

                            <div className="flex justify-end">
                                <Button type="submit" disabled={processing}>
                                    {processing ? 'Loading...' : 'Konfirmasi'}
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </div>
    );
}