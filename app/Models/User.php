<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Cashier\Billable;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'status',
        'password',
        'platform_type',
        'fcm_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getSubscriptionAttribute($value){
        if($value == '1'){
            return true;
        }else{
            return false;
        }
    }
    public function userprofile()
    {
      return $this->hasOne(UserProfile::class,'user_id','id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follower_lists', 'user_id', 'follower_id');
    }

    public function followings()
    {
        return $this->belongsToMany(User::class, 'follower_lists', 'follower_id', 'user_id');
    }

    public function TotalStar()
    {
        return $this->hasMany(GiveStar::class, 'user_id', 'id');
    }

    public function Following()
    {
        return $this->hasOne(FollowerList::class, 'follower_id', 'id')->where('id', auth()->id());
    }
}
