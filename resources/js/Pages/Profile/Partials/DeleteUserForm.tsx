// resources/js/Pages/Profile/Partials/DeleteUserForm.tsx
import { useForm } from '@inertiajs/react';
import { FormEventHandler, useRef, useState } from 'react';
import { Button } from "@/Components/ui/button"
import { Input } from "@/Components/ui/input"
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from "@/Components/ui/dialog"
import { AlertTriangle, Lock } from 'lucide-react';

export default function DeleteUserForm({
    className = '',
}: {
    className?: string;
}) {
    const [open, setOpen] = useState(false);
    const passwordInput = useRef<HTMLInputElement>(null);

    const {
        data,
        setData,
        delete: destroy,
        processing,
        reset,
        errors,
        clearErrors,
    } = useForm({
        password: '',
    });

    const deleteUser: FormEventHandler = (e) => {
        e.preventDefault();

        destroy(route('profile.destroy'), {
            preserveScroll: true,
            onSuccess: () => setOpen(false),
            onError: () => passwordInput.current?.focus(),
            onFinish: () => reset(),
        });
    };

    return (
        <section className={`space-y-6 ${className}`}>
            <header>
                <h2 className="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Hapus Akun
                </h2>
                <p className="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Setelah akun Anda dihapus, semua sumber daya dan data akan dihapus secara permanen.
                    Sebelum menghapus akun Anda, harap unduh data atau informasi yang ingin Anda simpan.
                </p>
            </header>

            <Dialog open={open} onOpenChange={setOpen}>
                <DialogTrigger asChild>
                    <Button variant="destructive">
                        Hapus Akun
                    </Button>
                </DialogTrigger>
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>
                            <div className="flex items-center gap-2 text-red-600">
                                <AlertTriangle className="h-5 w-5" />
                                Apakah Anda yakin ingin menghapus akun?
                            </div>
                        </DialogTitle>
                        <DialogDescription>
                            Setelah akun Anda dihapus, semua sumber daya dan data akan dihapus secara permanen.
                            Silakan masukkan password Anda untuk mengkonfirmasi bahwa Anda ingin menghapus akun Anda secara permanen.
                        </DialogDescription>
                    </DialogHeader>

                    <form onSubmit={deleteUser} className="space-y-4">
                        <div className="relative">
                            <Lock className="absolute left-3 top-3 h-4 w-4 text-gray-500" />
                            <Input
                                type="password"
                                ref={passwordInput}
                                value={data.password}
                                onChange={e => setData('password', e.target.value)}
                                className="pl-9"
                                placeholder="Password"
                            />
                        </div>
                        {errors.password && <p className="text-sm text-red-600">{errors.password}</p>}

                        <div className="flex justify-end gap-3">
                            <Button variant="outline" onClick={() => setOpen(false)}>
                                Batal
                            </Button>
                            <Button type="submit" variant="destructive" disabled={processing}>
                                {processing ? 'Menghapus...' : 'Hapus Akun'}
                            </Button>
                        </div>
                    </form>
                </DialogContent>
            </Dialog>
        </section>
    );
}