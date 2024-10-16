<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Constraint\IsFalse;

class Setting extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getMaintenanceAttribute(){
        if($this->maintenance == '1'){
            $name = true;
        }elseif($this->maintenance == '0'){
            $name = false;
        }else{
            $name = false;
        }
        return $name;
    }
}
