<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostComment extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded=[];

    public function getIsLikeAttribute($like){
        if($like == '0'){
            return false;
        }else{
            return true;
        }
    }
    public function ReplyComment(){
        return $this->hasMany(PostComment::class, 'post_comment_id', 'id');
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function LikedComment()
    {
        return $this->hasOne(LikePostComment::class, 'comment_id', 'id')->where('user_id', auth()->id());
    }
}
