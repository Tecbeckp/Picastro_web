<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Http;

trait  UploadImageTrait
{
    public function originalImageUpload($file, $destinationFolder, $chatImage = false)
    {
        if (!$file) {
            return null;
        }

        if($chatImage){
            $random = rand(1000,9999);
            $filename = time() . $random . '.' . $file->getClientOriginalExtension();
            $file->move(public_path($destinationFolder), $filename);
            $fileUrl = $destinationFolder . $filename;
        }else{
            $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.webp';
        $localFilePath = public_path('assets/uploads/postimage/' . time() . '-' . $fileName);
        $filePath = $destinationFolder . time() . '-' . $fileName;
        $image = Image::read($file)
                      ->resize()
                      ->save($localFilePath, 100);
    
        Storage::disk('s3')->put($filePath, file_get_contents($file));
    
        $fileUrl = Storage::disk('s3')->url($filePath);

        unlink($localFilePath);
        }
        

        return $fileUrl;
    }

    public function imageUpload($file, $destinationFolder)
    {
        if (!$file) {
            return null;
        }

        $random = rand(1000, 9999);
        $filename = time() . $random . '.webp';

        // Load the image file using the Intervention Image package
        $image = Image::read($file);

        // Get the original width and height
        $originalWidth = $image->width();
        $originalHeight = $image->height();

        // Calculate 70% of the original width and height
        $newWidth = round($originalWidth * 0.3);
        $newHeight = round($originalHeight * 0.3);

        // Resize the image to 70% of its original size
        $image->resize($newWidth, $newHeight, function ($constraint) {
            $constraint->aspectRatio();
        })->save(public_path($destinationFolder . $filename), 90);

        $file_path = $destinationFolder . $filename;
        return $file_path;
    }

    function getImageFileSizeFromUrl($url)
    {
        $response = Http::head($url);

        if ($response->successful() && $response->header('Content-Length')) {
            $fileSizeInBytes = $response->header('Content-Length');

            $fileSizeInKB = $fileSizeInBytes / 1024;
            $fileSizeInMB = $fileSizeInKB / 1024;

            return [
                'size_in_bytes' => $fileSizeInBytes,
                'size_in_kb' => round($fileSizeInKB, 2),
                'size_in_mb' => round($fileSizeInMB, 2),
            ];
        }

        return null;
    }
}
