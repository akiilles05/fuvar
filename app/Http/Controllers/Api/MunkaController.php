<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Munka;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MunkaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $munkak = Munka::with('fuvarozo')->get();
        return response()->json(['data' => $munkak]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
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
     * Display the specified resource.
     */
    public function show(Munka $munka): JsonResponse
    {
        return response()->json(['data' => $munka->load('fuvarozo')]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Munka $munka): JsonResponse
    {
        $validated = $request->validate([
            'kiindulasi_cim' => ['sometimes', 'required', 'string', 'max:255'],
            'erkezesi_cim' => ['sometimes', 'required', 'string', 'max:255'],
            'cimzett_neve' => ['sometimes', 'required', 'string', 'max:255'],
            'cimzett_telefonszama' => ['sometimes', 'required', 'string', 'max:32'],
            'fuvarozo_id' => ['sometimes', 'nullable', 'exists:fuvarozo,id'],
            'statusz' => ['sometimes', 'in:kiosztva,folyamatban,elvegezve,sikertelen'],
        ]);

        $munka->update($validated);

        return response()->json(['data' => $munka->load('fuvarozo')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Munka $munka): JsonResponse
    {
        $munka->delete();
        return response()->json(['message' => 'Munka törölve.']);
    }
}
