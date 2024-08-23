<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hospitalization extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'date_in',
        'date_out',
        'patient_id',
        'patient_familiar_name',
        'patient_familiar_phone',
    ];

    protected $appends = ['patient_name'];

    public function getPatientNameAttribute()
    {
        return $this->patient->name;
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
