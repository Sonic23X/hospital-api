<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'birthdate',
        'address',
        'phone',
        'gender',
    ];
    protected $appends = ['age'];

    public function getAgeAttribute()
    {
        return Carbon::parse($this->attributes['birthdate'])->age;
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords(strtolower($value));
    }

    public function appointments() :HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function customer_billing_data()
    {
        return $this->hasOne(CustomerBillingData::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
