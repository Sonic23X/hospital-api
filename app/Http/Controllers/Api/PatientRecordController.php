<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PatientRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PatientRecordController extends Controller
{
    public function store(Request $request, $patientId)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,doc,docx,jpeg,png,jpg,gif',
        ]);

        if ($request->file('file')) {
            $filePath = $request->file('file')->store('public/patient_records');

            $record = PatientRecord::create([
                'patient_id' => $patientId,
                'file_path' =>  Storage::url($filePath),
                'file_name' => $request->file('file')->getClientOriginalName(),
            ]);

            return response()->json($record, 201);
        }

        return response()->json(['message' => 'File upload failed'], 400);
    }

    public function destroy($id)
    {
        $record = PatientRecord::findOrFail($id);

        Storage::delete($record->file_path);

        $record->delete();

        return response()->json(['message' => 'File deleted successfully']);
    }

    public function index($patientId)
    {
        $records = PatientRecord::where('patient_id', $patientId)->get();

        return response()->json($records);
    }
}
