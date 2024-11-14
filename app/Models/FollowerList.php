<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FollowerList extends Model
{
  use HasFactory;
  protected $guarded = [];

  public function following()
  {
    return $this->hasOne(User::class, 'id', 'user_id')->whereNull('users.deleted_at');
  }

  public function follower()
  {
    return $this->hasOne(User::class, 'id', 'follower_id')->whereNull('users.deleted_at');
  }
  public function blockToUser()
  {
    return $this->hasOne(BlockToUser::class, 'block_user_id', 'follower_id')->where('user_id', auth()->id());
  }
  public function UserToBlock()
  {
    return $this->hasOne(BlockToUser::class, 'user_id', 'follower_id')->where('block_user_id', auth()->id());
  }

  public function FollowBlockToUser()
  {
    return $this->hasOne(BlockToUser::class, 'block_user_id', 'user_id')->where('user_id', auth()->id());
  }
  public function FollowUserToBlock()
  {
    return $this->hasOne(BlockToUser::class, 'user_id', 'user_id')->where('block_user_id', auth()->id());
  }
}
