<?php

namespace App\Models;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Slider extends Model
{
    use HasFactory;
    public $guarded=[];
    public $appends=['image_path'];

    public function getImagePathAttribute()
    {
        if ($this->image !== null && Storage::exists("public/uploads/sliders/".$this->image)) {
            return env("STORAGE_URL")."/uploads/sliders/".$this->image;
        }
        return Setting::first()->website_logo();
    }

    public function image()
    {
        if ($this->image !== null && Storage::exists("public/uploads/sliders/".$this->image)) {
            return env("STORAGE_URL")."/uploads/sliders/".$this->image;
        }
        return Setting::first()->website_logo();
    }
}
