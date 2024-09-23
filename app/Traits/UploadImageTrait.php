<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
trait  UploadImageTrait
{
    public function originalImageUpload($file, $destinationFolder)
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

    public function imageUpload($file, $destinationFolder)
    {
        if (!$file) {
            return null;
        }
        
        $random = rand(1000,9999);
        $filename = time() . $random . '.webp';
        // $fileSizeMB = $file->getSize() / (1024 * 1024);
        // $quality = $fileSizeMB > 50 ? 10 : 90;
        $image = Image::read($file);
        // Resize image
        $image->resize()->save(public_path($destinationFolder . $filename), 30);
        $file_path = $destinationFolder . $filename;
        return $file_path;
    }
}

?>
