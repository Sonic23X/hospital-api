<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FiscalRegime extends Model
{
    use HasFactory;
    protected $fillable = ['code', 'description'];

    protected $appends = ['concat_description'];

    public function getConcatDescriptionAttribute()
    {
        return $this->attributes['code']." - ".$this->attributes['description'];
    }
}
