// resources/js/Pages/LetterTemplates/Index.tsx
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
import { LetterTemplate } from '@/types/letter';
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
import { Plus, FileText, Edit } from 'lucide-react';

interface Props {
  templates: PaginatedResponse<LetterTemplate>;
  filters: {
    search?: string;
    type?: string;
    active?: boolean;
  };
}

export default function Index({ templates, filters }: Props) {
  const [search, setSearch] = useState(filters.search || '');
  const [type, setType] = useState(filters.type || '');

  return (
    <AuthenticatedLayout
      header={<h2 className="text-xl font-semibold leading-tight text-gray-800">Template Surat</h2>}
    >
      <Head title="Template Surat" />

      <div className="py-12">
        <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
          <Card className="p-6">
            <div className="mb-6 flex items-center justify-between">
              <div className="flex flex-1 items-center space-x-4">
                <Input 
                  placeholder="Cari template..." 
                  value={search}
                  onChange={(e) => setSearch(e.target.value)}
                  className="max-w-xs"
                />
                <Select value={type} onValueChange={setType}>
                  <SelectTrigger className="w-[180px]">
                    <SelectValue placeholder="Tipe Surat" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="all">Semua Tipe</SelectItem>
                    <SelectItem value="incoming">Surat Masuk</SelectItem>
                    <SelectItem value="outgoing">Surat Keluar</SelectItem>
                  </SelectContent>
                </Select>
              </div>
              <Button asChild>
                <Link href={route('letters.templates.create')}>
                  <Plus className="mr-2 h-4 w-4" />
                  Tambah Template
                </Link>
              </Button>
            </div>

            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>Nama</TableHead>
                  <TableHead>Kode</TableHead>
                  <TableHead>Tipe</TableHead>
                  <TableHead>Status</TableHead>
                  <TableHead>Pembuat</TableHead>
                  <TableHead>Aksi</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {templates.data.map((template) => (
                  <TableRow key={template.id}>
                    <TableCell>{template.name}</TableCell>
                    <TableCell>{template.code}</TableCell>
                    <TableCell>
                      <Badge>
                        {template.type === 'incoming' ? 'Surat Masuk' : 'Surat Keluar'}
                      </Badge>
                    </TableCell>
                    <TableCell>
                      <Badge variant={template.is_active ? 'default' : 'secondary'}>
                        {template.is_active ? 'Aktif' : 'Nonaktif'}
                      </Badge>
                    </TableCell>
                    <TableCell>{template.createdBy.name}</TableCell>
                    <TableCell>
                      <div className="flex space-x-2">
                        <Button
                          variant="ghost"
                          size="icon"
                          asChild
                        >
                          <Link href={route('letters.templates.show', template.id)}>
                            <FileText className="h-4 w-4" />
                          </Link>
                        </Button>
                        <Button
                          variant="ghost"
                          size="icon"
                          asChild
                        >
                          <Link href={route('letters.templates.edit', template.id)}>
                            <Edit className="h-4 w-4" />
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