<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaveObject extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded=[];

    public function user()
    {
      return $this->belongsTo(User::class);
    }

    public function postImage()
    {
      return $this->hasOne(PostImage::class,'id','post_image_id')->whereNull('deleted_at');
    }
}
