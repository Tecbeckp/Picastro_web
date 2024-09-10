<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trophy extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded=[];

    public function getIconAttribute($icon){
        if ($icon) {
            return asset($icon);
        }else{
            return '';
        }
    }
}
