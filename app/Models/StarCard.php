<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StarCard extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded=[];

    public function StarCardFilter()
    {
      return $this->hasMany(StarCardFilter::class);
    }

    public function MainSetup()
    {
        return $this->belongsTo(MainSetup::class, 'setup', 'id');
    }

    public function post()
    {
        return $this->belongsTo(PostImage::class, 'post_image_id', 'id');
    }
}
