// resources/js/Pages/OutgoingLetters/Index.tsx
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
import { OutgoingLetter } from '@/types/letter';
import { PaginatedResponse } from '@/types/pagination';
import { Badge } from '@/Components/ui/badge';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/Components/ui/select";
import { format } from 'date-fns';
import { 
  FileSearch,
  FileUp,
  Plus,
  Send,
  CheckCircle
} from 'lucide-react';

interface Props {
  letters: PaginatedResponse<OutgoingLetter>;
  filters: {
    search?: string;
    status?: string;
    priority?: string;
  };
}

export default function Index({ letters, filters }: Props) {
  const [search, setSearch] = useState(filters.search || '');
  const [status, setStatus] = useState(filters.status || '');
  const [priority, setPriority] = useState(filters.priority || '');

  const getPriorityColor = (priority: string) => {
    switch (priority) {
      case 'high':
        return 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
      case 'medium':
        return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
      case 'low':
        return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
      default:
        return 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
    }
  };

  const getStatusColor = (status: string) => {
    switch (status) {
      case 'draft':
        return 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
      case 'review': 
        return 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300';
      case 'approved':
        return 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300';
      case 'sent':
        return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
      default:
        return 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
    }
  };

  return (
    <AuthenticatedLayout
      header={<h2 className="text-xl font-semibold leading-tight text-gray-800">Surat Keluar</h2>}
    >
      <Head title="Surat Keluar" />

      <div className="py-12">
        <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
          <Card className="p-6">
            <div className="mb-6 flex items-center justify-between">
              <div className="flex flex-1 items-center space-x-4">
                <Input 
                  placeholder="Cari surat..." 
                  value={search}
                  onChange={(e) => setSearch(e.target.value)}
                  className="max-w-xs"
                />
                <Select value={status} onValueChange={setStatus}>
                  <SelectTrigger className="w-[180px]">
                    <SelectValue placeholder="Status" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="all">Semua Status</SelectItem>
                    <SelectItem value="draft">Draft</SelectItem>
                    <SelectItem value="review">Review</SelectItem>
                    <SelectItem value="approved">Disetujui</SelectItem>
                    <SelectItem value="sent">Terkirim</SelectItem>
                  </SelectContent>
                </Select>
                <Select value={priority} onValueChange={setPriority}>
                  <SelectTrigger className="w-[180px]">
                    <SelectValue placeholder="Prioritas" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="all">Semua Prioritas</SelectItem>
                    <SelectItem value="high">Tinggi</SelectItem>
                    <SelectItem value="medium">Sedang</SelectItem>
                    <SelectItem value="low">Rendah</SelectItem>
                  </SelectContent>
                </Select>
              </div>
              <Button asChild>
                <Link href={route('letters.outgoing.create')}>
                  <Plus className="mr-2 h-4 w-4" />
                  Buat Surat
                </Link>
              </Button>
            </div>

            <div className="relative overflow-x-auto">
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableHead>No. Surat</TableHead>
                    <TableHead>Penerima</TableHead>
                    <TableHead>Perihal</TableHead>
                    <TableHead>Tgl. Surat</TableHead>
                    <TableHead>Prioritas</TableHead>
                    <TableHead>Status</TableHead>
                    <TableHead className="w-[120px]">Aksi</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {letters.data.map((letter) => (
                    <TableRow key={letter.id}>
                      <TableCell>{letter.reference_number}</TableCell>
                      <TableCell>{letter.recipient}</TableCell>
                      <TableCell>{letter.subject}</TableCell>
                      <TableCell>
                        {format(new Date(letter.letter_date), 'dd/MM/yyyy')}
                      </TableCell>
                      <TableCell>
                        <Badge className={getPriorityColor(letter.priority)}>
                          {letter.priority}
                        </Badge>
                      </TableCell>
                      <TableCell>
                        <Badge className={getStatusColor(letter.status)}>
                          {letter.status}
                        </Badge>
                      </TableCell>
                      <TableCell>
                        <div className="flex space-x-2">
                          <Button
                            variant="ghost"
                            size="icon"
                            asChild
                          >
                            <Link href={route('letters.outgoing.show', letter.id)}>
                              <FileSearch className="h-4 w-4" />
                            </Link>
                          </Button>
                          {letter.status !== 'sent' && (
                            <Button
                              variant="ghost"
                              size="icon"
                              asChild
                            >
                              <Link href={route('letters.outgoing.edit', letter.id)}>
                                <FileUp className="h-4 w-4" />
                              </Link>
                            </Button>
                          )}
                          {letter.status === 'approved' && (
                            <Button
                              variant="ghost"
                              size="icon"
                              asChild
                            >
                              <Link href={route('letters.outgoing.send', letter.id)}>
                                <Send className="h-4 w-4" />
                              </Link>
                            </Button>
                          )}
                          {letter.status === 'review' && (
                            <Button
                              variant="ghost"
                              size="icon"
                              asChild
                            >
                              <Link href={route('letters.outgoing.approve', letter.id)}>
                                <CheckCircle className="h-4 w-4" />
                              </Link>
                            </Button>
                          )}
                        </div>
                      </TableCell>
                    </TableRow>
                  ))}
                </TableBody>
              </Table>
            </div>
          </Card>
        </div>
      </div>
    </AuthenticatedLayout>
  );
}