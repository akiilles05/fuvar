import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/input-error';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/react';
import { useState } from 'react';
import { Plus, Edit, Trash2, UserCheck } from 'lucide-react';

interface Fuvarozo {
    id: number;
    nev: string;
    email: string;
}

interface Munka {
    id: number;
    kiindulasi_cim: string;
    erkezesi_cim: string;
    cimzett_neve: string;
    cimzett_telefonszama: string;
    statusz: 'kiosztva' | 'folyamatban' | 'elvegezve' | 'sikertelen';
    fuvarozo?: Fuvarozo;
    created_at: string;
    updated_at: string;
}

interface MunkakProps {
    munkak: Munka[];
    fuvarozok: Fuvarozo[];
    currentFilter?: string | null;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Munkák kezelése',
        href: '/admin/munkak',
    },
];

const statuszOptions = [
    { value: 'kiosztva', label: 'Kiosztva' },
    { value: 'folyamatban', label: 'Folyamatban' },
    { value: 'elvegezve', label: 'Elvégezve' },
    { value: 'sikertelen', label: 'Sikertelen' },
] as const;

const statuszColors = {
    kiosztva: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
    folyamatban: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
    elvegezve: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
    sikertelen: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
};

export default function Munkak({ munkak, fuvarozok, currentFilter }: MunkakProps) {
    const [showCreateForm, setShowCreateForm] = useState(false);
    const [editingMunka, setEditingMunka] = useState<Munka | null>(null);
    const [assigningMunka, setAssigningMunka] = useState<Munka | null>(null);
    const [statusFilter, setStatusFilter] = useState<string>(currentFilter || '');

    const createForm = useForm({
        kiindulasi_cim: '',
        erkezesi_cim: '',
        cimzett_neve: '',
        cimzett_telefonszama: '',
        fuvarozo_id: '',
        statusz: 'kiosztva',
    });

    const editForm = useForm({
        kiindulasi_cim: '',
        erkezesi_cim: '',
        cimzett_neve: '',
        cimzett_telefonszama: '',
        fuvarozo_id: '',
        statusz: '',
    });

    const assignForm = useForm({
        fuvarozo_id: '',
    });

    const handleCreate = () => {
        createForm.post('/admin/munkak', {
            onSuccess: () => {
                setShowCreateForm(false);
                createForm.reset();
            },
        });
    };

    const handleEdit = (munka: Munka) => {
        setEditingMunka(munka);
        editForm.setData({
            kiindulasi_cim: munka.kiindulasi_cim,
            erkezesi_cim: munka.erkezesi_cim,
            cimzett_neve: munka.cimzett_neve,
            cimzett_telefonszama: munka.cimzett_telefonszama,
            fuvarozo_id: munka.fuvarozo?.id?.toString() || '',
            statusz: munka.statusz,
        });
    };

    const handleUpdate = () => {
        if (!editingMunka) return;
        editForm.patch(`/admin/munkak/${editingMunka.id}`, {
            onSuccess: () => {
                setEditingMunka(null);
                editForm.reset();
            },
        });
    };

    const handleDelete = (id: number) => {
        if (confirm('Biztosan törölni szeretné ezt a munkát?')) {
            router.delete(`/admin/munkak/${id}`);
        }
    };

    const handleAssign = (munka: Munka) => {
        setAssigningMunka(munka);
        assignForm.setData({
            fuvarozo_id: munka.fuvarozo?.id?.toString() || '',
        });
    };

    const handleAssignSubmit = () => {
        if (!assigningMunka) return;
        assignForm.patch(`/admin/munkak/${assigningMunka.id}/assign`, {
            onSuccess: () => {
                setAssigningMunka(null);
                assignForm.reset();
            },
        });
    };

    const handleStatusFilterChange = (value: string) => {
        setStatusFilter(value);
        const params = new URLSearchParams(window.location.search);
        if (value && value !== 'all') {
            params.set('statusz', value);
        } else {
            params.delete('statusz');
        }
        router.visit(`/admin/munkak?${params.toString()}`, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Munkák kezelése" />
            <div className="flex h-full flex-1 flex-col gap-6 overflow-x-auto rounded-xl p-4">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-bold">Munkák kezelése</h1>
                        <p className="text-muted-foreground mt-1">
                            Munkák létrehozása, módosítása és fuvarozókhoz rendelése
                        </p>
                    </div>
                    <div className="flex items-center gap-4">
                        <div className="flex items-center gap-2">
                            <Label htmlFor="status-filter">Szűrés státusz szerint:</Label>
                            <Select value={statusFilter} onValueChange={handleStatusFilterChange}>
                                <SelectTrigger className="w-48">
                                    <SelectValue placeholder="Összes munka" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="all">Összes munka</SelectItem>
                                    {statuszOptions.map((option) => (
                                        <SelectItem key={option.value} value={option.value}>
                                            {option.label}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                        </div>
                        <Button onClick={() => setShowCreateForm(true)}>
                            <Plus className="mr-2 h-4 w-4" />
                            Új munka
                        </Button>
                    </div>
                </div>

                {/* Create Form */}
                {showCreateForm && (
                    <Card>
                        <CardHeader>
                            <CardTitle>Új munka létrehozása</CardTitle>
                        </CardHeader>
                        <CardContent className="space-y-4">
                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <Label htmlFor="kiindulasi_cim">Kiindulási cím</Label>
                                    <Input
                                        id="kiindulasi_cim"
                                        value={createForm.data.kiindulasi_cim}
                                        onChange={(e) => createForm.setData('kiindulasi_cim', e.target.value)}
                                    />
                                    <InputError message={createForm.errors.kiindulasi_cim} />
                                </div>
                                <div>
                                    <Label htmlFor="erkezesi_cim">Érkezési cím</Label>
                                    <Input
                                        id="erkezesi_cim"
                                        value={createForm.data.erkezesi_cim}
                                        onChange={(e) => createForm.setData('erkezesi_cim', e.target.value)}
                                    />
                                    <InputError message={createForm.errors.erkezesi_cim} />
                                </div>
                            </div>
                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <Label htmlFor="cimzett_neve">Címzett neve</Label>
                                    <Input
                                        id="cimzett_neve"
                                        value={createForm.data.cimzett_neve}
                                        onChange={(e) => createForm.setData('cimzett_neve', e.target.value)}
                                    />
                                    <InputError message={createForm.errors.cimzett_neve} />
                                </div>
                                <div>
                                    <Label htmlFor="cimzett_telefonszama">Címzett telefonszáma</Label>
                                    <Input
                                        id="cimzett_telefonszama"
                                        value={createForm.data.cimzett_telefonszama}
                                        onChange={(e) => createForm.setData('cimzett_telefonszama', e.target.value)}
                                    />
                                    <InputError message={createForm.errors.cimzett_telefonszama} />
                                </div>
                            </div>
                            <div className="flex gap-2">
                                <Button onClick={handleCreate} disabled={createForm.processing}>
                                    Létrehozás
                                </Button>
                                <Button variant="outline" onClick={() => setShowCreateForm(false)}>
                                    Mégse
                                </Button>
                            </div>
                        </CardContent>
                    </Card>
                )}

                {/* Edit Form */}
                {editingMunka && (
                    <Card>
                        <CardHeader>
                            <CardTitle>Munka módosítása #{editingMunka.id}</CardTitle>
                        </CardHeader>
                        <CardContent className="space-y-4">
                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <Label htmlFor="edit_kiindulasi_cim">Kiindulási cím</Label>
                                    <Input
                                        id="edit_kiindulasi_cim"
                                        value={editForm.data.kiindulasi_cim}
                                        onChange={(e) => editForm.setData('kiindulasi_cim', e.target.value)}
                                    />
                                    <InputError message={editForm.errors.kiindulasi_cim} />
                                </div>
                                <div>
                                    <Label htmlFor="edit_erkezesi_cim">Érkezési cím</Label>
                                    <Input
                                        id="edit_erkezesi_cim"
                                        value={editForm.data.erkezesi_cim}
                                        onChange={(e) => editForm.setData('erkezesi_cim', e.target.value)}
                                    />
                                    <InputError message={editForm.errors.erkezesi_cim} />
                                </div>
                            </div>
                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <Label htmlFor="edit_cimzett_neve">Címzett neve</Label>
                                    <Input
                                        id="edit_cimzett_neve"
                                        value={editForm.data.cimzett_neve}
                                        onChange={(e) => editForm.setData('cimzett_neve', e.target.value)}
                                    />
                                    <InputError message={editForm.errors.cimzett_neve} />
                                </div>
                                <div>
                                    <Label htmlFor="edit_cimzett_telefonszama">Címzett telefonszáma</Label>
                                    <Input
                                        id="edit_cimzett_telefonszama"
                                        value={editForm.data.cimzett_telefonszama}
                                        onChange={(e) => editForm.setData('cimzett_telefonszama', e.target.value)}
                                    />
                                    <InputError message={editForm.errors.cimzett_telefonszama} />
                                </div>
                            </div>
                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <Label htmlFor="edit_statusz">Státusz</Label>
                                    <Select
                                        value={editForm.data.statusz}
                                        onValueChange={(value) => editForm.setData('statusz', value)}
                                    >
                                        <SelectTrigger>
                                            <SelectValue />
                                        </SelectTrigger>
                                        <SelectContent>
                                            {statuszOptions.map((option) => (
                                                <SelectItem key={option.value} value={option.value}>
                                                    {option.label}
                                                </SelectItem>
                                            ))}
                                        </SelectContent>
                                    </Select>
                                </div>
                                <div>
                                    <Label htmlFor="edit_fuvarozo_id">Fuvarozó</Label>
                                    <Select
                                        value={editForm.data.fuvarozo_id}
                                        onValueChange={(value) => editForm.setData('fuvarozo_id', value)}
                                    >
                                        <SelectTrigger>
                                            <SelectValue placeholder="Válasszon fuvarozót" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="">Nincs hozzárendelve</SelectItem>
                                            {fuvarozok.map((fuvarozo) => (
                                                <SelectItem key={fuvarozo.id} value={fuvarozo.id.toString()}>
                                                    {fuvarozo.nev}
                                                </SelectItem>
                                            ))}
                                        </SelectContent>
                                    </Select>
                                </div>
                            </div>
                            <div className="flex gap-2">
                                <Button onClick={handleUpdate} disabled={editForm.processing}>
                                    Módosítás
                                </Button>
                                <Button variant="outline" onClick={() => setEditingMunka(null)}>
                                    Mégse
                                </Button>
                            </div>
                        </CardContent>
                    </Card>
                )}

                {/* Assign Form */}
                {assigningMunka && (
                    <Card>
                        <CardHeader>
                            <CardTitle>Fuvarozó hozzárendelése #{assigningMunka.id}</CardTitle>
                        </CardHeader>
                        <CardContent className="space-y-4">
                            <div>
                                <Label htmlFor="assign_fuvarozo_id">Fuvarozó</Label>
                                <Select
                                    value={assignForm.data.fuvarozo_id}
                                    onValueChange={(value) => assignForm.setData('fuvarozo_id', value)}
                                >
                                    <SelectTrigger>
                                        <SelectValue placeholder="Válasszon fuvarozót" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {fuvarozok.map((fuvarozo) => (
                                            <SelectItem key={fuvarozo.id} value={fuvarozo.id.toString()}>
                                                {fuvarozo.nev}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </div>
                            <div className="flex gap-2">
                                <Button onClick={handleAssignSubmit} disabled={assignForm.processing}>
                                    Hozzárendelés
                                </Button>
                                <Button variant="outline" onClick={() => setAssigningMunka(null)}>
                                    Mégse
                                </Button>
                            </div>
                        </CardContent>
                    </Card>
                )}

                {/* Munkák List */}
                {munkak.length === 0 ? (
                    <Card>
                        <CardContent className="py-12 text-center">
                            <p className="text-muted-foreground">Nincsenek munkák</p>
                        </CardContent>
                    </Card>
                ) : (
                    <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                        {munkak.map((munka) => (
                            <Card key={munka.id}>
                                <CardHeader>
                                    <div className="flex items-center justify-between">
                                        <CardTitle className="text-lg">Munka #{munka.id}</CardTitle>
                                        <span
                                            className={`rounded-full px-2 py-1 text-xs font-medium ${statuszColors[munka.statusz]}`}
                                        >
                                            {statuszOptions.find((opt) => opt.value === munka.statusz)?.label}
                                        </span>
                                    </div>
                                </CardHeader>
                                <CardContent className="space-y-4">
                                    <div>
                                        <p className="text-sm font-medium text-muted-foreground">Kiindulási cím</p>
                                        <p className="text-sm">{munka.kiindulasi_cim}</p>
                                    </div>
                                    <div>
                                        <p className="text-sm font-medium text-muted-foreground">Érkezési cím</p>
                                        <p className="text-sm">{munka.erkezesi_cim}</p>
                                    </div>
                                    <div>
                                        <p className="text-sm font-medium text-muted-foreground">Címzett neve</p>
                                        <p className="text-sm">{munka.cimzett_neve}</p>
                                    </div>
                                    <div>
                                        <p className="text-sm font-medium text-muted-foreground">Címzett telefonszáma</p>
                                        <p className="text-sm">{munka.cimzett_telefonszama}</p>
                                    </div>
                                    <div>
                                        <p className="text-sm font-medium text-muted-foreground">Fuvarozó</p>
                                        <p className="text-sm">{munka.fuvarozo?.nev || 'Nincs hozzárendelve'}</p>
                                    </div>
                                    <div className="flex gap-2">
                                        <Button size="sm" variant="outline" onClick={() => handleEdit(munka)}>
                                            <Edit className="h-4 w-4" />
                                        </Button>
                                        <Button size="sm" variant="outline" onClick={() => handleAssign(munka)}>
                                            <UserCheck className="h-4 w-4" />
                                        </Button>
                                        <Button size="sm" variant="destructive" onClick={() => handleDelete(munka.id)}>
                                            <Trash2 className="h-4 w-4" />
                                        </Button>
                                    </div>
                                </CardContent>
                            </Card>
                        ))}
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
