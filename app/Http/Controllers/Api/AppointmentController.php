<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appointments = Appointment::with(["patient", "doctor", "specialty"])->get();
        return response()->json($appointments);
    }

    public function show($id)
    {
        return Appointment::with(["patient", "doctor", "specialty"])->findOrFail($id);
    }

    public function store(Request $request)
    {
        $request->validate([
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'consultation_type' => 'required|string',
            'doctor_id' => 'required|integer|exists:doctors,id',
            'specialty_id' => 'required|integer|exists:specialties,id',
            'reason' => 'required|string',
            'patient_name' => 'required|string|max:255',
            'patient_birthdate' => 'required|date',
            'patient_address' => 'required|string|max:255',
            'patient_phone' => 'required|string|max:20',
            'patient_gender' => 'required|string|max:10',
        ]);

        $existingAppointment = Appointment::where('doctor_id', $request->doctor_id)
            ->where('appointment_date', $request->appointment_date)
            ->where('appointment_time', $request->appointment_time)
            ->first();

        if ($existingAppointment) {
            return response()->json(['message' => 'Ya existe una cita con este doctor a la misma hora.'], 409);
        }

        $patient = Patient::create([
            'name' => $request->patient_name,
            'birthdate' => $request->patient_birthdate,
            'address' => $request->patient_address,
            'phone' => $request->patient_phone,
            'gender' => $request->patient_gender,
        ]);

        $appointment = Appointment::create([
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'consultation_type' => $request->consultation_type,
            'doctor_id' => $request->doctor_id,
            'specialty_id' => $request->specialty_id,
            'reason' => $request->reason,
            'patient_id' => $patient->id
        ]);

        return response()->json($appointment, 201);
    }

    public function update(Request $request, Appointment $appointment)
    {
        $request->validate([
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'consultation_type' => 'required|string',
            'doctor_id' => 'required|integer|exists:doctors,id',
            'specialty_id' => 'required|integer|exists:specialties,id',
            'reason' => 'required|string',
            'patient_name' => 'required|string|max:255',
            'patient_birthdate' => 'required|date',
            'patient_address' => 'required|string|max:255',
            'patient_phone' => 'required|string|max:20',
            'patient_gender' => 'required|string|max:10',
        ]);

        if (now()->greaterThan($appointment->appointment_date)) {
            return response()->json(['message' => 'No se puede editar una cita pasada.'], 422);
        }

        $appointment->patient->update([
            'name' => $request->patient_name,
            'birthdate' => $request->patient_birthdate,
            'address' => $request->patient_address,
            'phone' => $request->patient_phone,
            'gender' => $request->patient_gender,
        ]);

        $appointment->update([
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'consultation_type' => $request->consultation_type,
            'doctor_id' => $request->doctor_id,
            'specialty_id' => $request->specialty_id,
            'reason' => $request->reason
        ]);

        return response()->json($appointment, 200);
    }

    public function cancel(Appointment $appointment)
    {
        if (now()->greaterThan($appointment->appointment_date)) {
            return response()->json(['message' => 'No se puede cancelar una cita pasada.'], 422);
        }

        $appointment->status = 'cancelled';
        $appointment->save();

        return response()->json(['message' => 'Cita cancelada correctamente.'], 200);
    }

    public function done(Appointment $appointment)
    {
        $appointment->status = 'done';
        $appointment->save();

        return response()->json(['message' => 'Cita terminada correctamente.'], 200);
    }
}
