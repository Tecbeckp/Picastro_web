<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VoteImage extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded=[];
    
  
    public function user()
    {
      return $this->belongsTo(User::class, 'post_user_id','id');
    }
    
     public function postImage()
    {
      return $this->hasOne(PostImage::class,'id','post_image_id');
    }
}
