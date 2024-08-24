<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hospitalization;
use Illuminate\Http\Request;

class HospitalizationController extends Controller
{
    public function index()
    {
        $hospitalizations = Hospitalization::all();
        return response()->json($hospitalizations);
    }



    public function store(Request $request)
    {
        $validated = $request->validate([
            'date_in' => 'required|date',
            'date_out' => 'nullable|date|after_or_equal:date_in',
            // 'room_id' => 'required|exists:rooms,id',
            'patient_id' => 'required|exists:patients,id',
            'room_id' => 'required',
            'patient_familiar_name' => 'required|string|max:255',
            'patient_familiar_phone' => 'required|string|max:255',
        ]);

        $hospitalization = Hospitalization::create($validated);

        return response()->json($hospitalization, 201);
    }

    public function show($id)
    {
        return Hospitalization::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $hospitalization = Hospitalization::find($id);

        if ($hospitalization->date_out !== null) {
            return response()->json(['message' => 'No se puede actualizar una hospitalización con fecha de salida.'], 403);
        }

        $validated = $request->validate([
            'date_in' => 'required|date',
            'date_out' => 'nullable|date|after_or_equal:date_in',
            // 'room_id' => 'required|exists:rooms,id',
            'patient_id' => 'required|exists:patients,id',
            'room_id' => 'required',
            'patient_familiar_name' => 'required|string|max:255',
            'patient_familiar_phone' => 'required|string|max:255',
        ]);

        $hospitalization->update($validated);

        return response()->json($hospitalization, 200);
    }

    public function destroy($id)
    {
        $hospitalization = Hospitalization::findOrFail($id);

        if ($hospitalization->date_out !== null) {
            return response()->json(['message' => 'No se puede eliminar el registro porque ya tiene una fecha de salida.'], 403);
        }

        $hospitalization->delete();

        return response()->json(null, 204);
    }
}
