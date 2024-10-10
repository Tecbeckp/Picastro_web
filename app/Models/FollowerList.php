<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FollowerList extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function following()
    {
      return $this->hasOne(User::class,'id','user_id')->whereNull('deleted_at');
    }

    public function follower()
    {
      return $this->hasOne(User::class,'id','follower_id')->whereNull('deleted_at');
    }
 
}
