<?php

namespace App\Traits;

use App\Models\User;

trait ApiResponseTrait
{
    public function success($message=null, $data)
    {
        $user_id = auth()->id();
        $user  = User::where('id',$user_id)->first();
        if(isset($user)){
            $is_subscription  = $user->subscription;
            $trial_period_end = $user->trial_period_end;
        }else{
            $is_subscription  = null;
            $trial_period_end = null;
        }
        return response()->json(['success'=> true, 'message'=>$message, 'data' => $data, 'is_subscription' => $is_subscription, 'trial_period_end' => $trial_period_end],200);
    }
    public function error($message, $code=200)
    {
        $user_id = auth()->id();
        $user  = User::where('id',$user_id)->first();
        if(isset($user)){
            $is_subscription  = $user->subscription;
            $trial_period_end = $user->trial_period_end;
        }else{
            $is_subscription  = null;
            $trial_period_end = null;
        }
        return response()->json(['success'=> false, 'message' => $message, 'is_subscription' => $is_subscription, 'trial_period_end' => $trial_period_end], $code);
    }
}
