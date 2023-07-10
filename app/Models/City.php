<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends Model
{
    use HasFactory;
    use HasTranslations;

    public $translatable = ['name'];

    public $guarded=['id','created_at','updated_at'];

    public function country()
    {
        return $this->belongsTo(\App\Models\Country::class);
    }
    public function states()
    {
        return $this->hasMany(\App\Models\State::class);
    }

}
