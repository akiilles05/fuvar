<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Fuvarozo;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FuvarozoController extends Controller
{
    /**
     * Update the status (szerepkor) of the specified fuvarozo.
     */
    public function updateStatus(Request $request, Fuvarozo $fuvarozo): JsonResponse
    {
        $validated = $request->validate([
            'szerepkor' => ['required', 'in:admin,fuvarozo'],
        ]);

        $fuvarozo->update($validated);

        return response()->json(['data' => $fuvarozo]);
    }
}
