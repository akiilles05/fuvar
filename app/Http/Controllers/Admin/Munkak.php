<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Munka;
use App\Models\Fuvarozo;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;
use App\Notifications\MunkaFailedNotification;
use Illuminate\Support\Facades\Notification;

class Munkak extends Controller
{
    /**
     * List all munkák.
     */
    public function index(Request $request): Response
    {
        if (auth('fuvarozo')->user()->szerepkor !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $query = Munka::with('fuvarozo');

        // Status-based filtering
        if ($request->has('statusz') && !empty($request->statusz)) {
            $query->where('statusz', $request->statusz);
        }

        $munkak = $query->get();
        $fuvarozok = Fuvarozo::where('szerepkor', 'fuvarozo')->get();

        return Inertia::render('admin/munkak', [
            'munkak' => $munkak,
            'fuvarozok' => $fuvarozok,
            'currentFilter' => $request->statusz ?? null,
        ]);
    }

    /**
     * Create a new munka.
     */
    public function store(Request $request): JsonResponse
    {
        if (auth('fuvarozo')->user()->szerepkor !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'kiindulasi_cim' => ['required', 'string', 'max:255'],
            'erkezesi_cim' => ['required', 'string', 'max:255'],
            'cimzett_neve' => ['required', 'string', 'max:255'],
            'cimzett_telefonszama' => ['required', 'string', 'max:32'],
            'fuvarozo_id' => ['nullable', 'exists:fuvarozo,id'],
            'statusz' => ['nullable', 'in:kiosztva,folyamatban,elvegezve,sikertelen'],
        ]);

        if (!array_key_exists('statusz', $validated) || is_null($validated['statusz'])) {
            $validated['statusz'] = 'kiosztva';
        }

        $munka = Munka::create($validated);

        return response()->json(['data' => $munka], 201);
    }

    /**
     * Update an existing munka.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        if (auth('fuvarozo')->user()->szerepkor !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $munka = Munka::findOrFail($id);
        $oldStatus = $munka->statusz;

        $validated = $request->validate([
            'kiindulasi_cim' => ['sometimes', 'required', 'string', 'max:255'],
            'erkezesi_cim' => ['sometimes', 'required', 'string', 'max:255'],
            'cimzett_neve' => ['sometimes', 'required', 'string', 'max:255'],
            'cimzett_telefonszama' => ['sometimes', 'required', 'string', 'max:32'],
            'fuvarozo_id' => ['sometimes', 'nullable', 'exists:fuvarozo,id'],
            'statusz' => ['sometimes', 'in:kiosztva,folyamatban,elvegezve,sikertelen'],
        ]);

        $munka->update($validated);

        // Send notification if status changed to 'sikertelen'
        if (isset($validated['statusz']) && $validated['statusz'] === 'sikertelen' && $oldStatus !== 'sikertelen') {
            $admins = Fuvarozo::where('szerepkor', 'admin')->get();
            Notification::send($admins, new MunkaFailedNotification($munka));
        }

        return response()->json(['data' => $munka]);
    }

    /**
     * Delete a munka.
     */
    public function destroy(int $id): JsonResponse
    {
        if (auth('fuvarozo')->user()->szerepkor !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $munka = Munka::findOrFail($id);
        $munka->delete();

        return response()->json(['message' => 'Munka törölve.']);
    }

    /**
     * Fuvarozó hozzárendelése munkához.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id Munka ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignFuvarozo(Request $request, int $id): JsonResponse
    {
        if (auth('fuvarozo')->user()->szerepkor !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $munka = Munka::findOrFail($id);

        $validated = $request->validate([
            'fuvarozo_id' => ['required', 'exists:fuvarozo,id'],
        ]);

        $munka->fuvarozo_id = $validated['fuvarozo_id'];
        $munka->statusz = $munka->statusz ?? 'kiosztva';
        $munka->save();

        return response()->json(['data' => $munka]);
    }
}
