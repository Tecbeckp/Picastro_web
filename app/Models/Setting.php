<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Constraint\IsFalse;

class Setting extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getMaintenanceIosAttribute($value){
        if($value == '1'){
            $name = true;
        }elseif($value == '0'){
            $name = false;
        }else{
            $name = false;
        }
        return $name;
    }

    public function getMaintenanceAndroidAttribute($value){
        if($value == '1'){
            $name = true;
        }elseif($value == '0'){
            $name = false;
        }else{
            $name = false;
        }
        return $name;
    }
}
