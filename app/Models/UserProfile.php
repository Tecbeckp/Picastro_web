<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class UserProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $appends = [
        'max_post_mbs',
        'gallery_password'
    ];
    public function Following()
    {
        return $this->hasOne(FollowerList::class, 'follower_id', 'user_id')->where('user_id', auth()->id());
    }

    public function getMaxPostMbsAttribute()
    {
        if (Auth::check()) {
            $data = SubscriptionPlan::where('id', auth()->user()->subscription_id)->first();
            if ($data) {
                return "$data->image_size_limit";
            } else {
                return "0";
            }
        } else {
            return "0";
        }
    }

    public function getGalleryPasswordAttribute(){
        return $this->gallery_password;
    }
}
