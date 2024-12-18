<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded=[];
    public function Following()
    {
        return $this->hasOne(FollowerList::class, 'follower_id', 'user_id')->where('user_id', auth()->id());
    }
}
