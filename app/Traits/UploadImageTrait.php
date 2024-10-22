<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Http;

trait  UploadImageTrait
{
    public function originalImageUpload($file, $destinationFolder)
    {
        if (!$file) {
            return null;
        }
    

        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.webp';
        $localFilePath = public_path($destinationFolder . time() . '-' . $fileName);

        $image = Image::read($file)
                      ->resize()
                      ->save('assets/uploads/postimage/', 75);
    
        Storage::disk('s3')->put($destinationFolder . time() . '-' . $fileName, file_get_contents($localFilePath));
    
        $fileUrl = Storage::disk('s3')->url($destinationFolder . time() . '-' . $fileName);
    
        unlink($localFilePath);
    
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
            $constraint->aspectRatio(); // Maintain aspect ratio
        })->save(public_path($destinationFolder . $filename), 90); // Save with quality set to 90%

        $file_path = $destinationFolder . $filename;
        return $file_path;
    }

    function getImageFileSizeFromUrl($url)
    {
        // Fetch headers only (use HEAD request to get the Content-Length)
        $response = Http::head($url);

        // Check if the Content-Length header is present
        if ($response->successful() && $response->header('Content-Length')) {
            $fileSizeInBytes = $response->header('Content-Length');

            // Convert to KB or MB
            $fileSizeInKB = $fileSizeInBytes / 1024;
            $fileSizeInMB = $fileSizeInKB / 1024;

            return [
                'size_in_bytes' => $fileSizeInBytes,
                'size_in_kb' => round($fileSizeInKB, 2),
                'size_in_mb' => round($fileSizeInMB, 2),
            ];
        }

        return null; // If the file size could not be determined
    }
}
