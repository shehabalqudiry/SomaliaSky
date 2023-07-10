<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AnnouncementAttribute extends Model
{
    use HasFactory;
    use HasTranslations;
    public $table = 'announcements_attributes';
    public $guarded=['id','created_at','updated_at'];
    public $translatable = ['name', 'value'];


    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }
    public function attributeRelation()
    {
        return $this->belongsTo(CategoryAttribute::class, 'name');
    }
}
