<?php

namespace App\Traits;

trait ApiResponseTrait
{
    public function success($message=null, $data)
    {
        return response()->json(['success'=> true, 'message'=>$message, 'data' => $data],200);
    }
    public function error($message, $code=200)
    {
        return response()->json(['success'=> false, 'message' => $message], $code);
    }
}
