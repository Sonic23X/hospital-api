<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
