<?php

namespace App\Models;

use App\Models\Setting;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
        'rate',
    ];


    public function getProfilePhotoUrlAttribute($value)
    {
        if ($this->avatar !== null && Storage::exists("public/uploads/users/".$this->avatar)) {
            return env("STORAGE_URL")."/uploads/users/".$this->avatar;
        }
        return Setting::first()->website_logo();
    }

    public function getUserAvatar()
    {
        if ($this->avatar !== null && Storage::exists("public/uploads/users/".$this->avatar)) {
            return env("STORAGE_URL")."/uploads/users/".$this->avatar;
        }
        return Setting::first()->website_logo();
    }

    public function scopeWithoutTimestamps()
    {
        $this->timestamps = false;
        return $this;
    }

    public function skills()
    {
        return $this->hasMany(UserSkill::class);
    }


    public function jobs()
    {
        return $this->hasMany(UserJob::class);
    }


    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }


    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }

    public function store()
    {
        return $this->hasOne(Store::class);
    }

    public function subscription()
    {
        return $this->hasMany(Subscription::class);
    }

    public function getRateAttribute()
    {
        return $this->rate_my()->avg('stars');
    }

    public function rate_my()
    {
        return $this->hasMany(Rate::class, 'rated_user');
    }

    public function rate_other()
    {
        return $this->hasMany(Subscription::class, 'user_id');
    }

    public function traffics()
    {
        return $this->hasMany(RateLimit::class);
    }
    public function report_errors()
    {
        return $this->hasMany(ReportError::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
