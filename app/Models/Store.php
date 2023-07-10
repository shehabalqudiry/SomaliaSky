<?php

namespace App\Models;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Store extends Model
{
    use HasFactory;

    public $guarded=['id','created_at','updated_at'];
    public $appends=['rate'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getRateAttribute()
    {
        return $this->user->rate_my->avg('stars');
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

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class);
    }

    public function city()
    {
        return $this->belongsTo(\App\Models\City::class, 'city_id');
    }

    public function avatar_image()
    {
        if ($this->avatar !== null && Storage::exists("public/uploads/stores/$this->id/".$this->avatar)) {
            return env("STORAGE_URL")."/uploads/stores/$this->id/".$this->avatar;
        }
        return Setting::first()->website_logo();
    }

    public function cover()
    {
        if ($this->cover_image !== null && Storage::exists("public/uploads/uploads/".$this->cover_image)) {
            return env("STORAGE_URL")."/uploads/uploads/".$this->cover_image;
        }
        return Setting::first()->website_logo();
    }
}
