<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getFollowAttribute($value){
        if($value == '1'){
            return true;
        }else{
            return false;
        }
    }

    public function getPostAttribute($value){
        if($value == '1'){
            return true;
        }else{
            return false;
        }
    }

    public function getTrophyAttribute($value){
        if($value == '1'){
            return true;
        }else{
            return false;
        }
    }

    public function getStarAttribute($value){
        if($value == '1'){
            return true;
        }else{
            return false;
        }
    }

    public function getCommentAttribute($value){
        if($value == '1'){
            return true;
        }else{
            return false;
        }
    }

    public function getCommentReplyAttribute($value){
        if($value == '1'){
            return true;
        }else{
            return false;
        }
    }

    public function getLikeCommentAttribute($value){
        if($value == '1'){
            return true;
        }else{
            return false;
        }
    }
    public function getOtherAttribute($value){
        if($value == '1'){
            return true;
        }else{
            return false;
        }
    }
}
