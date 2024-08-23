<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'doctor_id', 'appointment_date', 'appointment_time', 
        'consultation_type', 'reason', 'status'
    ];

    public function patient() :BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public static function validateAppointment($date, $time, $doctorId)
    {
        return !self::where('appointment_date', $date)
                    ->where('appointment_time', $time)
                    ->where('doctor_id', $doctorId)
                    ->exists();
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending')
                     ->where('appointment_date', '>=', now());
    }
}
