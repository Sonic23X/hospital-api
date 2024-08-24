<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
    use HasFactory;
    use HasFactory;

    protected $fillable = ['name'];

    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'doctor_specialty');
    }
}

