<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use PhpParser\Node\Stmt\Block;

class PostImage extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $appends = [
        'gold_trophy',
        'silver_trophy',
        'bronze_trophy',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function ObjectType()
    {
        return $this->belongsTo(ObjectType::class)->select('id', 'name');
    }
    public function Bortle()
    {
        return $this->belongsTo(Bortle::class)->select('id', 'bortle_number');
    }
    public function ObserverLocation()
    {
        return $this->belongsTo(ObserverLocation::class)->select('id', 'name');
    }
    public function ApproxLunarPhase()
    {
        return $this->belongsTo(ApproxLunarPhase::class)->select('id', 'number');
    }
    public function Telescope()
    {
        return $this->belongsTo(Telescope::class)->select('id', 'name');
    }
    public function GiveStar()
    {
        return $this->hasOne(GiveStar::class, 'post_image_id', 'id')->where('user_id', auth()->id());
    }
    public function totalStar()
    {
        return $this->hasMany(GiveStar::class);
    }
    public function StarCard()
    {
        return $this->hasOne(StarCard::class, 'post_image_id', 'id');
    }
    public function votedTrophy()
    {
        return $this->hasOne(VoteImage::class, 'post_image_id', 'id')->where('user_id', auth()->id());
    }

    public function totalGoldTrophies()
    {
        return $this->hasMany(VoteImage::class, 'post_image_id', 'id')->where('trophy_id', 1);
    }
    public function Follow()
    {
        return $this->hasOne(FollowerList::class, 'user_id', 'user_id')->where('follower_id', auth()->id());
    }

    public function Follower()
    {
        return $this->hasMany(FollowerList::class, 'user_id', 'user_id');
    }
    public function blockToUser()
    {
        return $this->hasOne(BlockToUser::class, 'block_user_id', 'user_id')->where('user_id', auth()->id());
    }
    public function UserToBlock()
    {
        return $this->hasOne(BlockToUser::class, 'user_id', 'user_id')->where('block_user_id', auth()->id());
    }

    public function userHidePost()
    {
        return $this->hasOne(HidePost::class, 'post_id', 'id')->where('user_id', auth()->id());
    }

    public function getLocationAttribute()
    {
        if (in_array($this->observer_location_id, [1, 2, 3, 4, 6])) {
            $name = 'NH';
        } elseif (in_array($this->observer_location_id, [5, 7, 8])) {
            $name = 'SH';
        } else {
            $name = null;
        }
        return $name;
    }


    public function getImageAttribute($image)
    {
        if ($image) {
            $decodedImage = json_decode($image, true); // Decode as an array
            $img = [];
            if (is_array($decodedImage)) {
                foreach ($decodedImage as $value) {
                    $img[] = asset($value);
                }
                return $img;
            } else {
                $img[] = asset($image);
                return $img;
            }
        }
        return '';
    }

    public function getOriginalImageAttribute($image)
    {
        if ($image) {
            $decodedImage = json_decode($image, true); // Decode as an array
            $img = [];
            if (is_array($decodedImage)) {
                foreach ($decodedImage as $value) {
                    $img[] = $value;
                }
                return $img;
            } else {
                $img[] = $image;
                return $img;
            }
        }
        return '';
    }
    public function getOnlyImageAndDescriptionAttribute($only_image_and_description)
    {
        if ($only_image_and_description == '0') {
            return false;
        } else {
            return true;
        }
    }

    public function getGoldTrophyAttribute()
    {
        $data = VoteImage::where('trophy_id', 1)->where('post_image_id', $this->id)->first();
        if ($data) {
            return true;
        } else {
            return false;
        }
    }

    public function getSilverTrophyAttribute()
    {
        $data = VoteImage::where('trophy_id', 2)->where('post_image_id', $this->id)->first();
        if ($data) {
            return true;
        } else {
            return false;
        }
    }


    public function getBronzeTrophyAttribute()
    {
        $data = VoteImage::where('trophy_id', 3)->where('post_image_id', $this->id)->first();
        if ($data) {
            return true;
        } else {
            return false;
        }
    }

    public function toArray()
    {
        $array = parent::toArray();
        unset($array['object_type_id']);
        unset($array['bortle_id']);
        unset($array['observer_location_id']);
        unset($array['approx_lunar_phase_id']);
        unset($array['telescope_id']);
        return $array;
    }
}
