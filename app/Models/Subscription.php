<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    public $guarded=['id','created_at','updated_at'];

    public function package()
    {
        return $this->belongsTo(\App\Models\Package::class);
    }
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function getStartDateAttribute($value)
    {
        if($value==null)return;
        return \Carbon::parse($value)->format('Y-m-d\ H:i');
    }
    public function getEndDateAttribute($value)
    {
        if($value==null)return;
        return \Carbon::parse($value)->format('Y-m-d\ H:i');
    }
}
