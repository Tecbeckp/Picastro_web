<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiveStar extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function user()
    {
      return $this->belongsTo(User::class, 'post_user_id','id');
    }
    public function postImage()
    {
      return $this->hasOne(PostImage::class,'id','post_image_id');
    }

    public function GivenUser(){
      return $this->belongsTo(User::class, 'user_id','id');
    }
}
