<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeekOfTheImage extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function postImage()
    {
      return $this->hasOne(PostImage::class,'id','post_id');
    }
}
