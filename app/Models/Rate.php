<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Rate extends Model
{

    protected $guarded = [];

    public function rated_user()
    {
        return $this->belongsTo(User::class, 'rated_user');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


}
