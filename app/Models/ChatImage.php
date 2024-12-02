<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatImage extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function getOriginalImageAttribute($key){
        if($key){
            return asset($key);
        }else{
            return '';
        }
    }

    public function getThumbnailAttribute($key){
        if($key){
            return asset($key);
        }else{
            return '';
        }
    }
}
