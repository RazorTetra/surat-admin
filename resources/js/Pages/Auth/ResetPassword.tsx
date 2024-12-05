// resources/js/Pages/Auth/ResetPassword.tsx
import { Head, useForm } from '@inertiajs/react';
import { FormEventHandler } from 'react';
import { Card, CardContent, CardHeader, CardTitle } from "@/Components/ui/card"
import { Input } from "@/Components/ui/input"
import { Button } from "@/Components/ui/button"
import { Mail, Lock } from 'lucide-react';

export default function ResetPassword({ token, email }: { token: string; email: string; }) {
    const { data, setData, post, processing, errors } = useForm({
        token: token,
        email: email,
        password: '',
        password_confirmation: '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('password.store'));
    };

    return (
        <div className="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 py-16 dark:from-gray-900 dark:to-gray-800">
            <Head title="Reset Password - SURAT PTIK" />
            <div className="container mx-auto flex flex-col items-center px-6">
                <div className="mb-8 flex items-center space-x-2">
                    <Mail className="h-8 w-8 text-blue-600" />
                    <span className="text-2xl font-bold text-gray-900 dark:text-white">SURAT PTIK</span>
                </div>
                <Card className="w-full max-w-md">
                    <CardHeader>
                        <CardTitle className="text-center">Reset Password</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <form onSubmit={submit} className="space-y-4">
                            <div className="space-y-2">
                                <label className="text-sm font-medium">Email</label>
                                <div className="relative">
                                    <Mail className="absolute left-3 top-3 h-4 w-4 text-gray-500" />
                                    <Input type="email" value={data.email} className="pl-9" onChange={e => setData('email', e.target.value)} />
                                </div>
                                {errors.email && <p className="text-sm text-red-600">{errors.email}</p>}
                            </div>
                            <div className="space-y-2">
                                <label className="text-sm font-medium">Password Baru</label>
                                <div className="relative">
                                    <Lock className="absolute left-3 top-3 h-4 w-4 text-gray-500" />
                                    <Input type="password" value={data.password} className="pl-9" onChange={e => setData('password', e.target.value)} />
                                </div>
                                {errors.password && <p className="text-sm text-red-600">{errors.password}</p>}
                            </div>
                            <div className="space-y-2">
                                <label className="text-sm font-medium">Konfirmasi Password Baru</label>
                                <div className="relative">
                                    <Lock className="absolute left-3 top-3 h-4 w-4 text-gray-500" />
                                    <Input type="password" value={data.password_confirmation} className="pl-9" onChange={e => setData('password_confirmation', e.target.value)} />
                                </div>
                                {errors.password_confirmation && <p className="text-sm text-red-600">{errors.password_confirmation}</p>}
                            </div>
                            <Button type="submit" className="w-full" disabled={processing}>
                                {processing ? 'Loading...' : 'Reset Password'}
                            </Button>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </div>
    );
}