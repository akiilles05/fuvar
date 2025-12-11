<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Fuvarozo;

class CarrierAuthController extends Controller
{
    public function showLoginForm(): Response
    {
        return Inertia::render('auth/login', [
            'status' => session('status'),
            'canResetPassword' => false,
            'canRegister' => false,
        ]);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $fuvarozo = Fuvarozo::where('email', $validated['email'])->first();

        if ($fuvarozo && Hash::check($validated['password'], $fuvarozo->jelszo)) {
            Auth::guard('fuvarozo')->login($fuvarozo, $request->boolean('remember'));
            $request->session()->regenerate();

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Hibás email vagy jelszó!',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('fuvarozo')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
