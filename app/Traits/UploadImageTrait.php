<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait  UploadImageTrait
{
    public function imageUpload($file, $destinationFolder)
    {
        if (!$file) {
            return null;
        }

         // Define the file name and path
         $filePath = $destinationFolder . time() . '-' . $file->getClientOriginalName();

         // Store the file on S3
         Storage::disk('s3')->put($filePath, file_get_contents($file));
 
         // Get the file URL
         $fileUrl = Storage::disk('s3')->url($filePath);

        return $fileUrl;
    }
}

?>
