<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryAttribute extends Model
{
    use HasFactory;
    use HasTranslations;

    public $translatable = ['name'];

    public $guarded=['id','created_at','updated_at'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
