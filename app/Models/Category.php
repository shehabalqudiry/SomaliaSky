<?php

namespace App\Models;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    use HasTranslations;

    public $translatable = ['title', 'slug'];

    public $guarded=[];
    public $appends=['url', 'image_path'];


    public function category()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function subCats()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function attributes()
    {
        return $this->hasMany(CategoryAttribute::class);
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }

    public function stores()
    {
        return $this->hasMany(Store::class);
    }

    public function getUrlAttribute()
    {
        // return route('front.category.show',$this);
    }

    public function getImagePathAttribute()
    {
        if ($this->image !== null && Storage::exists("public/uploads/categories/".$this->image)) {
            return env("STORAGE_URL")."/uploads/categories/".$this->image;
        }
        return Setting::first()->website_logo();
    }


    public function image()
    {
        if ($this->image !== null && Storage::exists("public/uploads/categories/".$this->image)) {
            return env("STORAGE_URL")."/uploads/categories/".$this->image;
        }
        return Setting::first()->website_logo();
    }
}
