<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class State extends Model
{
    use HasFactory;
    use HasTranslations;
    
    public $translatable = ['name'];
    public $guarded=['id','created_at','updated_at'];


    public function city()
    {
        return $this->belongsTo(\App\Models\City::class);
    }
}
