<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ObjectType extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded=[];

    public function saveObject(){
        return $this->hasMany(SaveObject::class);
    }

    public function getIconAttribute($icon){
        if($icon){
            return asset($icon);
        }else{
            return '';
        }
    }
}
