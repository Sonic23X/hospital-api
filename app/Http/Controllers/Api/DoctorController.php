<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('time') && $request->has('date')) {
            $datetime = new \DateTime($request->input('time'));
            $date = new \DateTime($request->input('date'));
            $dayOfWeek = strtolower($date->format('l'));
            $time = $datetime->format('H:i:s');
    
            $doctors = Doctor::with('specialties')->whereHas('schedules', function ($query) use ($dayOfWeek, $time) {
                $query->where('day_of_week', $dayOfWeek)
                      ->where('start_time', '<=', $time)
                      ->where('end_time', '>=', $time);
            })->get();
        } else {
            $doctors = Doctor::with('specialties')->get();
        }
        
        return response()->json($doctors, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'specialty_ids' => 'array|exists:specialties,id',
        ]);

        $doctor = Doctor::create($request->only('name'));

        if ($request->has('specialty_ids')) {
            $doctor->specialties()->sync($request->specialty_ids);
        }

        return response()->json($doctor->load('specialties'), 201);
    }

    public function show($id)
    {
        return Doctor::with('specialties')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'specialty_ids' => 'array|exists:specialties,id',
        ]);

        $doctor = Doctor::findOrFail($id);
        $doctor->update($request->only('name'));

        if ($request->has('specialty_ids')) {
            $doctor->specialties()->sync($request->specialty_ids);
        }

        return response()->json($doctor->load('specialties'), 200);
    }

    public function destroy($id)
    {
        Doctor::findOrFail($id)->delete();

        return response()->json(null, 204);
    }
}
