import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import AppLayout from '@/layouts/app-layout';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/react';
import { useState } from 'react';

interface Munka {
    id: number;
    kiindulasi_cim: string;
    erkezesi_cim: string;
    cimzett_neve: string;
    cimzett_telefonszama: string;
    statusz: 'kiosztva' | 'folyamatban' | 'elvegezve' | 'sikertelen';
    created_at: string;
    updated_at: string;
}

interface DashboardProps {
    szerepkor: string;
    munkak?: Munka[];
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
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

export default function Dashboard({ szerepkor, munkak = [] }: DashboardProps) {
    const [updating, setUpdating] = useState<number | null>(null);

    const handleStatusChange = (munkaId: number, newStatus: string) => {
        setUpdating(munkaId);
        router.patch(
            `/fuvarozo/munkak/${munkaId}/statusz`,
            { statusz: newStatus },
            {
                preserveScroll: true,
                onFinish: () => setUpdating(null),
            }
        );
    };

    if (szerepkor === 'fuvarozo') {
        return (
            <AppLayout breadcrumbs={breadcrumbs}>
                <Head title="Dashboard" />
                <div className="flex h-full flex-1 flex-col gap-6 overflow-x-auto rounded-xl p-4">
                    <div>
                        <h1 className="text-2xl font-bold">Munkák</h1>
                        <p className="text-muted-foreground mt-1">
                            Itt láthatod a neked kiosztott munkákat és
                            módosíthatod azok státuszát
                        </p>
                    </div>

                    {munkak.length === 0 ? (
                        <Card>
                            <CardContent className="py-12 text-center">
                                <p className="text-muted-foreground">
                                    Nincsenek kiosztott munkák
                                </p>
                            </CardContent>
                        </Card>
                    ) : (
                        <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                            {munkak.map((munka) => (
                                <Card key={munka.id}>
                                    <CardHeader>
                                        <div className="flex items-center justify-between">
                                            <CardTitle className="text-lg">
                                                Munka #{munka.id}
                                            </CardTitle>
                                            <span
                                                className={`rounded-full px-2 py-1 text-xs font-medium ${statuszColors[munka.statusz]}`}
                                            >
                                                {
                                                    statuszOptions.find(
                                                        (opt) =>
                                                            opt.value ===
                                                            munka.statusz
                                                    )?.label
                                                }
                                            </span>
                                        </div>
                                    </CardHeader>
                                    <CardContent className="space-y-4">
                                        <div>
                                            <p className="text-sm font-medium text-muted-foreground">
                                                Kiindulási cím
                                            </p>
                                            <p className="text-sm">
                                                {munka.kiindulasi_cim}
                                            </p>
                                        </div>
                                        <div>
                                            <p className="text-sm font-medium text-muted-foreground">
                                                Érkezési cím
                                            </p>
                                            <p className="text-sm">
                                                {munka.erkezesi_cim}
                                            </p>
                                        </div>
                                        <div>
                                            <p className="text-sm font-medium text-muted-foreground">
                                                Címzett neve
                                            </p>
                                            <p className="text-sm">
                                                {munka.cimzett_neve}
                                            </p>
                                        </div>
                                        <div>
                                            <p className="text-sm font-medium text-muted-foreground">
                                                Címzett telefonszáma
                                            </p>
                                            <p className="text-sm">
                                                {munka.cimzett_telefonszama}
                                            </p>
                                        </div>
                                        <div>
                                            <p className="text-sm font-medium text-muted-foreground mb-2">
                                                Státusz módosítása
                                            </p>
                                            <Select
                                                value={munka.statusz}
                                                onValueChange={(value) =>
                                                    handleStatusChange(
                                                        munka.id,
                                                        value
                                                    )
                                                }
                                                disabled={
                                                    updating === munka.id
                                                }
                                            >
                                                <SelectTrigger>
                                                    <SelectValue />
                                                </SelectTrigger>
                                                <SelectContent>
                                                    {statuszOptions.map(
                                                        (option) => (
                                                            <SelectItem
                                                                key={
                                                                    option.value
                                                                }
                                                                value={
                                                                    option.value
                                                                }
                                                            >
                                                                {option.label}
                                                            </SelectItem>
                                                        )
                                                    )}
                                                </SelectContent>
                                            </Select>
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

    // Admin dashboard
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div>
                    <h1 className="text-2xl font-bold">Admin Dashboard</h1>
                    <p className="text-muted-foreground mt-1">
                        Admin funkciók kezelése
                    </p>
                </div>
                <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    <Card>
                        <CardHeader>
                            <CardTitle>Munkák kezelése</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <p className="text-sm text-muted-foreground mb-4">
                                Munkák létrehozása, módosítása és fuvarozókhoz rendelése
                            </p>
                            <Button asChild>
                                <a href="/admin/munkak">Munkák kezelése</a>
                            </Button>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </AppLayout>
    );
}
