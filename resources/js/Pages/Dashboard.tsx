// resources/js/Pages/Dashboard.tsx
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card';
import { Button } from '@/Components/ui/button';
import { 
    Mail, 
    FileUp, 
    FileDown, 
    Clock, 
    FileText, 
    ChevronRight
} from 'lucide-react';
import { Link } from '@inertiajs/react';

export default function Dashboard() {
    const navigationCards = [
        {
            title: 'Manajemen Surat Masuk',
            icon: FileDown,
            description: 'Kelola semua surat masuk, disposisi, dan tindak lanjut',
            route: 'letters.incoming.index',
            color: 'bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-300'
        },
        {
            title: 'Manajemen Surat Keluar',
            icon: FileUp,
            description: 'Buat dan kelola surat keluar serta tracking status',
            route: 'letters.outgoing.index',
            color: 'bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-300'
        },
        {
            title: 'Format Surat',
            icon: FileText,
            description: 'Kelola template dan format surat yang tersedia',
            route: 'letters.templates.index',
            color: 'bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300'
        },
        {
            title: 'Surat Pending',
            icon: Clock,
            description: 'Pantau surat yang memerlukan tindak lanjut',
            route: 'letters.pending.index',
            color: 'bg-yellow-100 dark:bg-yellow-900 text-yellow-600 dark:text-yellow-300'
        }
    ];

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

                    {/* Navigation Cards */}
                    <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                        {navigationCards.map((card) => (
                            <Link key={card.route} href={route(card.route)}>
                                <Card className="cursor-pointer transition-all hover:shadow-lg">
                                    <CardContent className="p-6">
                                        <div className="flex items-center justify-between">
                                            <div className={`flex h-12 w-12 items-center justify-center rounded-lg ${card.color}`}>
                                                <card.icon className="h-6 w-6" />
                                            </div>
                                            <ChevronRight className="h-5 w-5 text-gray-400" />
                                        </div>
                                        <h3 className="mt-4 text-lg font-semibold text-gray-900 dark:text-white">
                                            {card.title}
                                        </h3>
                                        <p className="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                            {card.description}
                                        </p>
                                    </CardContent>
                                </Card>
                            </Link>
                        ))}
                    </div>

                    {/* Recent Letters */}
                    <Card>
                        <CardHeader>
                            <div className="flex items-center justify-between">
                                <CardTitle>Surat Terbaru</CardTitle>
                                <Button variant="ghost" className="flex items-center gap-2">
                                    Lihat Semua
                                    <ChevronRight className="h-4 w-4" />
                                </Button>
                            </div>
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