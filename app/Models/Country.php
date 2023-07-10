<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{
    use HasFactory;
    use HasTranslations;

    public $translatable = ['name'];
    public $guarded=['id','created_at','updated_at'];
    public $appends=['image_path'];


    public function cities()
    {
        return $this->hasMany(\App\Models\City::class);
    }

    public function currency()
    {
        return $this->belongsTo(\App\Models\Currency::class);
    }


    public function getImagePathAttribute(){
        if($this->flag==null)
            return env('DEFAULT_IMAGE');
        else
            return env("STORAGE_URL")."/uploads/countries/".$this->flag;
    }

}
