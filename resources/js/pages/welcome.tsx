import { Head, Link } from '@inertiajs/react';

export default function Welcome() {
    return (
        <>
            <Head title="Welcome" />
            <div className="flex min-h-screen flex-col items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 p-6">
                <div className="w-full max-w-md bg-white rounded-lg shadow-md p-8 flex flex-col items-center">
                    <h1 className="text-3xl font-bold mb-4 text-black">Üdvözlünk az oldalon!</h1>
                    <p className="mb-6 text-gray-700 text-center">
                        Ez egy egyszerű welcome oldal.<br/>
                        Jelentkezz be vagy regisztrálj az induláshoz.
                    </p>
                    <div className="flex gap-4">
                        <Link
                            href="/login"
                            className="px-6 py-2 rounded bg-blue-600 text-white hover:bg-blue-700 transition"
                        >
                            Bejelentkezés
                        </Link>
                        <Link
                            href="/register"
                            className="px-6 py-2 rounded bg-gray-200 text-gray-900 hover:bg-gray-300 transition"
                        >
                            Regisztráció
                        </Link>
                    </div>
                </div>
            </div>
        </>
    );
}