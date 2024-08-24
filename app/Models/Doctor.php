<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function specialties()
    {
        return $this->belongsToMany(Specialty::class, 'doctor_specialty');
    }

    public function schedules()
    {
        return $this->hasMany(DoctorSchedule::class);
    }
}
