// resources/js/Pages/Auth/VerifyEmail.tsx
import { Head, Link, useForm } from '@inertiajs/react';
import { FormEventHandler } from 'react';
import { Card, CardContent, CardHeader, CardTitle } from "@/Components/ui/card"
import { Button } from "@/Components/ui/button"
import { Mail, AlertCircle } from 'lucide-react';

export default function VerifyEmail({ status }: { status?: string }) {
    const { post, processing } = useForm({});

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('verification.send'));
    };

    return (
        <div className="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 py-16 dark:from-gray-900 dark:to-gray-800">
            <Head title="Verifikasi Email - SURAT PTIK" />
            <div className="container mx-auto flex flex-col items-center px-6">
                <div className="mb-8 flex items-center space-x-2">
                    <Mail className="h-8 w-8 text-blue-600" />
                    <span className="text-2xl font-bold text-gray-900 dark:text-white">SURAT PTIK</span>
                </div>
                <Card className="w-full max-w-md">
                    <CardHeader>
                        <CardTitle className="text-center">Verifikasi Email</CardTitle>
                    </CardHeader>
                    <CardContent className="space-y-4">
                        <div className="flex items-start space-x-4 rounded-lg bg-blue-50 p-4 dark:bg-blue-900/50">
                            <AlertCircle className="mt-0.5 h-5 w-5 text-blue-600 dark:text-blue-400" />
                            <p className="text-sm text-gray-600 dark:text-gray-300">
                                Terima kasih telah mendaftar! Mohon verifikasi alamat email Anda dengan mengklik tautan yang baru saja kami kirimkan. Jika Anda tidak menerima email tersebut, kami akan dengan senang hati mengirimkan yang baru.
                            </p>
                        </div>

                        {status === 'verification-link-sent' && (
                            <div className="rounded-lg bg-green-50 p-4 text-sm text-green-600 dark:bg-green-900/50 dark:text-green-400">
                                Link verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.
                            </div>
                        )}

                        <form onSubmit={submit} className="flex items-center justify-between">
                            <Button type="submit" disabled={processing}>
                                {processing ? 'Mengirim...' : 'Kirim Ulang Email Verifikasi'}
                            </Button>

                            <Link
                                href={route('logout')}
                                method="post"
                                as="button"
                                className="text-sm text-blue-600 hover:text-blue-500 dark:text-blue-400"
                            >
                                Keluar
                            </Link>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </div>
    );
}
