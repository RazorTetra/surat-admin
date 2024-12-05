// resources/js/Pages/Dashboard.tsx
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card';
import { Mail, FileUp, FileDown, Clock } from 'lucide-react';

export default function Dashboard() {
    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Dashboard Admin
                </h2>
            }
        >
            <Head title="Dashboard" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                    {/* Stats Overview */}
                    <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                        <Card>
                            <CardContent className="flex items-center p-6">
                                <div className="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900">
                                    <Mail className="h-6 w-6 text-blue-600 dark:text-blue-300" />
                                </div>
                                <div className="ml-4">
                                    <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Total Surat</p>
                                    <h3 className="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">128</h3>
                                </div>
                            </CardContent>
                        </Card>
                        
                        <Card>
                            <CardContent className="flex items-center p-6">
                                <div className="flex h-12 w-12 items-center justify-center rounded-lg bg-green-100 dark:bg-green-900">
                                    <FileDown className="h-6 w-6 text-green-600 dark:text-green-300" />
                                </div>
                                <div className="ml-4">
                                    <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Surat Masuk</p>
                                    <h3 className="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">64</h3>
                                </div>
                            </CardContent>
                        </Card>

                        <Card>
                            <CardContent className="flex items-center p-6">
                                <div className="flex h-12 w-12 items-center justify-center rounded-lg bg-purple-100 dark:bg-purple-900">
                                    <FileUp className="h-6 w-6 text-purple-600 dark:text-purple-300" />
                                </div>
                                <div className="ml-4">
                                    <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Surat Keluar</p>
                                    <h3 className="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">42</h3>
                                </div>
                            </CardContent>
                        </Card>

                        <Card>
                            <CardContent className="flex items-center p-6">
                                <div className="flex h-12 w-12 items-center justify-center rounded-lg bg-yellow-100 dark:bg-yellow-900">
                                    <Clock className="h-6 w-6 text-yellow-600 dark:text-yellow-300" />
                                </div>
                                <div className="ml-4">
                                    <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Pending</p>
                                    <h3 className="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">22</h3>
                                </div>
                            </CardContent>
                        </Card>
                    </div>

                    {/* Recent Letters */}
                    <Card>
                        <CardHeader>
                            <CardTitle>Surat Terbaru</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div className="divide-y divide-gray-200 dark:divide-gray-700">
                                {[1, 2, 3].map((item) => (
                                    <div key={item} className="flex items-center py-4">
                                        <div className="mr-4 flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900">
                                            <Mail className="h-5 w-5 text-blue-600 dark:text-blue-300" />
                                        </div>
                                        <div className="flex-1">
                                            <h4 className="text-sm font-medium text-gray-900 dark:text-white">
                                                Surat Undangan Rapat Jurusan
                                            </h4>
                                            <p className="text-sm text-gray-500 dark:text-gray-400">
                                                Diterima: 2 jam yang lalu
                                            </p>
                                        </div>
                                        <span className="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-300">
                                            Selesai
                                        </span>
                                    </div>
                                ))}
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}