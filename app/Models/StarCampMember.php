<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StarCampMember extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded=[];

    public function user(){
        return $this->hasOne(User::class,'id','member_id');
    }

    public function starcamp(){
        return $this->belongsTo(StarCamp::class, 'star_camp_id');
    }

    public function memberStarcamp(){
        return $this->hasMany(StarCamp::class, 'created_by', 'member_id');
    }

    public function Post(){
        return $this->hasMany(PostImage::class, 'user_id', 'member_id');
    }


}
