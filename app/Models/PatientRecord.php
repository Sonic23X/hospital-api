<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientRecord extends Model
{
    use HasFactory;
    protected $fillable = ['patient_id', 'file_path', 'file_name'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
