<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlockToUser extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function blockedUser(){
        return $this->hasOne(User::class, 'id', 'block_user_id');
    }
}
