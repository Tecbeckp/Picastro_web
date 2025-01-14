<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function postImage()
    {
      return $this->hasOne(PostImage::class,'id','post_image_id');
    }
}
