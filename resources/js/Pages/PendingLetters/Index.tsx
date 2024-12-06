// resources/js/Pages/PendingLetters/Index.tsx
import { useState } from 'react';
import { Head, Link } from '@inertiajs/react';
import { Card } from '@/Components/ui/card';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/Components/ui/table";
import { Badge } from '@/Components/ui/badge';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { FileSearch, FileUp } from 'lucide-react';

interface PendingLetter {
  id: number;
  reference_number: string;
  subject: string;
  priority: string;
  status: string;
  type: 'incoming' | 'outgoing';
  created_at: string;
}

interface Props {
  letters: {
    data: PendingLetter[];
    meta: any;
  };
  filters: {
    search?: string;
    priority?: string;
  };
}

export default function Index({ letters, filters }: Props) {
  const [search, setSearch] = useState(filters.search || '');

  const getPriorityColor = (priority: string) => {
    switch (priority) {
      case 'high':
        return 'bg-red-100 text-red-800';
      case 'medium':
        return 'bg-yellow-100 text-yellow-800';
      case 'low':
        return 'bg-green-100 text-green-800';
      default:
        return 'bg-gray-100 text-gray-800';
    }
  };

  return (
    <AuthenticatedLayout
      header={<h2 className="text-xl font-semibold leading-tight text-gray-800">Surat Pending</h2>}
    >
      <Head title="Surat Pending" />

      <div className="py-12">
        <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
          <Card className="p-6">
            <div className="mb-6">
              <Input 
                placeholder="Cari surat..." 
                value={search}
                onChange={(e) => setSearch(e.target.value)}
                className="max-w-xs"
              />
            </div>

            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>No. Surat</TableHead>
                  <TableHead>Perihal</TableHead>
                  <TableHead>Tipe</TableHead>
                  <TableHead>Status</TableHead>
                  <TableHead>Prioritas</TableHead>
                  <TableHead>Aksi</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {letters.data.map((letter) => (
                  <TableRow key={letter.id}>
                    <TableCell>{letter.reference_number}</TableCell>
                    <TableCell>{letter.subject}</TableCell>
                    <TableCell>
                      <Badge>
                        {letter.type === 'incoming' ? 'Surat Masuk' : 'Surat Keluar'}
                      </Badge>
                    </TableCell>
                    <TableCell>
                      <Badge>
                        {letter.status}
                      </Badge>
                    </TableCell>
                    <TableCell>
                      <Badge className={getPriorityColor(letter.priority)}>
                        {letter.priority}
                      </Badge>
                    </TableCell>
                    <TableCell>
                      <div className="flex space-x-2">
                        <Button
                          variant="ghost"
                          size="icon"
                          asChild
                        >
                          <Link href={route('letters.pending.show', { 
                            id: letter.id,
                            type: letter.type
                          })}>
                            <FileSearch className="h-4 w-4" />
                          </Link>
                        </Button>
                        <Button
                          variant="ghost"
                          size="icon"
                          asChild
                        >
                          <Link href={route(letter.type === 'incoming' ? 'letters.incoming.edit' : 'letters.outgoing.edit', letter.id)}>
                            <FileUp className="h-4 w-4" />
                          </Link>
                        </Button>
                      </div>
                    </TableCell>
                  </TableRow>
                ))}
              </TableBody>
            </Table>
          </Card>
        </div>
      </div>
    </AuthenticatedLayout>
  );
}