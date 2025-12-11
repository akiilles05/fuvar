<?php

namespace App\Http\Controllers;

use App\Models\Munka;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $user = Auth::guard('fuvarozo')->user();

        if ($user->szerepkor === 'fuvarozo') {
            $munkak = Munka::where('fuvarozo_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();

            return Inertia::render('dashboard', [
                'szerepkor' => $user->szerepkor,
                'munkak' => $munkak,
            ]);
        }

        // Admin dashboard (később implementálható)
        return Inertia::render('dashboard', [
            'szerepkor' => $user->szerepkor,
        ]);
    }
}
