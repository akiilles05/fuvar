<?php

namespace App\Http\Controllers\Fuvarozo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Munka;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class Munkak extends Controller
{
    /**
     * Megjeleníti a bejelentkezett fuvarozónak kiosztott munkákat,
     * azok státuszával és a címzett adataival.
     */
    public function index(Request $request)
    {
        $fuvarozo = Auth::guard('fuvarozo')->user();

        $munkak = Munka::where('fuvarozo_id', $fuvarozo->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return redirect()->route('dashboard');
    }

    /**
     * A fuvarozó módosíthatja egy neki kiosztott munka státuszát.
     *
     * Lehetséges státuszok:
     * - kiosztva
     * - folyamatban
     * - elvegezve
     * - sikertelen
     */
    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $fuvarozo = Auth::guard('fuvarozo')->user();

        $munka = Munka::where('id', $id)
            ->where('fuvarozo_id', $fuvarozo->id)
            ->firstOrFail();

        $validated = $request->validate([
            'statusz' => ['required', 'in:kiosztva,folyamatban,elvegezve,sikertelen'],
        ]);

        $munka->update([
            'statusz' => $validated['statusz'],
        ]);

        return redirect()->route('dashboard')->with('success', 'Munka státusza frissítve!');
    }
}
