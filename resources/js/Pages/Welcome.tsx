// resources/js/Pages/Welcome.tsx
import { Head, Link } from '@inertiajs/react';
import { PageProps } from '@/types';
import { Mail, FileText, Clock, UserCircle } from 'lucide-react';

export default function Welcome({
    auth,
}: PageProps<{ laravelVersion: string; phpVersion: string }>) {
    return (
        <>
            <Head title="Welcome - Sistem Surat PTIK" />
            <div className="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-900 dark:to-gray-800">
                <div className="relative flex min-h-screen flex-col">
                    {/* Navbar */}
                    <nav className="fixed w-full bg-white/80 shadow-sm backdrop-blur-sm dark:bg-gray-900/80">
                        <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                            <div className="flex h-16 justify-between">
                                <div className="flex items-center">
                                    <Mail className="h-8 w-8 text-blue-600" />
                                    <span className="ml-2 text-xl font-bold text-gray-900 dark:text-white">
                                        SIRAT PTIK
                                    </span>
                                </div>
                                <div className="flex items-center space-x-4">
                                    {auth.user ? (
                                        <Link
                                            href={route('dashboard')}
                                            className="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-500"
                                        >
                                            Dashboard
                                        </Link>
                                    ) : (
                                        <>
                                            <Link
                                                href={route('login')}
                                                className="rounded-lg px-4 py-2 text-sm font-semibold text-gray-700 transition hover:text-blue-600 dark:text-gray-300"
                                            >
                                                Log in
                                            </Link>
                                        </>
                                    )}
                                </div>
                            </div>
                        </div>
                    </nav>

                    {/* Hero Section */}
                    <main className="flex flex-1 flex-col items-center justify-center px-6 pt-16 pb-8">
                        <div className="text-center">
                            <h1 className="text-4xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-6xl">
                                Sistem Informasi Persuratan
                            </h1>
                            <h2 className="mt-2 text-xl font-semibold text-gray-600 dark:text-gray-300 sm:text-2xl">
                                Jurusan Pendidikan Teknologi Informasi dan Komunikasi
                            </h2>
                            <p className="mt-4 text-lg text-gray-600 dark:text-gray-400">
                                Kelola dokumen dan surat menyurat dengan efisien dan terorganisir
                            </p>
                        </div>

                        {/* Features */}
                        <div className="mt-16 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                            <div className="rounded-xl bg-white p-8 shadow-lg dark:bg-gray-800">
                                <FileText className="h-12 w-12 text-blue-600" />
                                <h3 className="mt-4 text-xl font-semibold text-gray-900 dark:text-white">
                                    Manajemen Surat
                                </h3>
                                <p className="mt-2 text-gray-600 dark:text-gray-400">
                                    Kelola surat masuk dan keluar dengan sistem digital yang terstruktur
                                </p>
                            </div>
                            <div className="rounded-xl bg-white p-8 shadow-lg dark:bg-gray-800">
                                <Clock className="h-12 w-12 text-blue-600" />
                                <h3 className="mt-4 text-xl font-semibold text-gray-900 dark:text-white">
                                    Tracking Real-time
                                </h3>
                                <p className="mt-2 text-gray-600 dark:text-gray-400">
                                    Pantau status dan progress surat secara real-time
                                </p>
                            </div>
                            <div className="rounded-xl bg-white p-8 shadow-lg dark:bg-gray-800">
                                <UserCircle className="h-12 w-12 text-blue-600" />
                                <h3 className="mt-4 text-xl font-semibold text-gray-900 dark:text-white">
                                    Akses Terkontrol
                                </h3>
                                <p className="mt-2 text-gray-600 dark:text-gray-400">
                                    Sistem keamanan berlapis dengan manajemen akses pengguna
                                </p>
                            </div>
                        </div>
                    </main>

                    {/* Footer */}
                    <footer className="py-8 text-center text-sm text-gray-600 dark:text-gray-400">
                        © {new Date().getFullYear()} Sistem Informasi Persuratan PTIK. All rights reserved.
                    </footer>
                </div>
            </div>
        </>
    );
}