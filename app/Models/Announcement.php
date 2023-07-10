<?php

namespace App\Models;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Announcement extends Model
{
    use HasFactory;
    use HasTranslations;

    public $translatable = ['title', 'description'];
    protected $guarded=[];

    public function getPriceAttribute($value)
    {
        return number_format($value);
    }
    public function imagesArray()
    {
        $images = [];
        if ($this->images==null) {
            $images = [Setting::first()->website_logo()];
        } else {
            foreach (explode(',', $this->images) as $key => $img) {
                // if (Storage::exists("public/uploads/uploads/".$img)) {
                $images[] = env("STORAGE_URL")."/uploads/uploads/".$img;
                // } else {
                // $images[] = Setting::first()->website_logo();
                // }
            }
        }

        return $images;
    }

    public function getCategory()
    {
        $category = Category::where('id', $this->category_id)->first();
        $cat = '';
        if ($category) {
            $parent = $category->category->title;
            $cat = $parent .' - ' . $category->title;
        }
        return $cat;
    }
    public function getRouteKeyName()
    {
        return 'number';
        // return 'slug';
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }


    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class);
    }

    public function AnnouncementAttribute()
    {
        return $this->hasMany(\App\Models\AnnouncementAttribute::class);
    }

    public function city()
    {
        return $this->belongsTo(\App\Models\City::class);
    }

    public function currency()
    {
        return $this->belongsTo(\App\Models\Currency::class);
    }
}
