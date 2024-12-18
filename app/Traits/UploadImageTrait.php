<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Http;

trait  UploadImageTrait
{
    public function originalImageUpload($file, $destinationFolder, $chatImage = false, $multiple = false)
    {
        if (!$file) {
            return null;
        }

        if ($chatImage) {
            $random = rand(1000, 9999);
            $filename = time() . $random . '.' . $file->getClientOriginalExtension();
            $file->move(public_path($destinationFolder), $filename);
            $fileUrl = $destinationFolder . $filename;
            return $fileUrl;
        } elseif ($multiple) {
            $imgs = [];
            foreach($file as $img){
            $fileName = pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME) . '.webp';
            $filePath = $destinationFolder . time() . '-'. auth()->id() . $fileName;
            Storage::disk('s3')->put($filePath, file_get_contents($img));
            $fileUrl = Storage::disk('s3')->url($filePath);
            $imgs[] = $fileUrl;
            }
            return $imgs;
        } else {
            $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.webp';
            $filePath = $destinationFolder . time() . '-' . $fileName;
            Storage::disk('s3')->put($filePath, file_get_contents($file));
            $fileUrl = Storage::disk('s3')->url($filePath);
            return $fileUrl;
        }
    }

    public function imageUpload($file, $destinationFolder, $multiple = false)
    {
        if (!$file) {
            return null;
        }

        if ($multiple) {
            $imgs = [];
            foreach ($file as $img) {
                $random = rand(1000, 9999);
                $filename = time() . $random . '.' . auth()->id() . '.webp';

                $image = Image::read($img);

                // Get the original width and height
                $originalWidth = $image->width();
                $originalHeight = $image->height();

                // Calculate 70% of the original width and height
                $newWidth = round($originalWidth * 0.3);
                $newHeight = round($originalHeight * 0.3);

                // Resize the image to 90% of its original size
                $image->resize($newWidth, $newHeight, function ($constraint) {
                    $constraint->aspectRatio();
                })->save(public_path($destinationFolder . $filename), 90);

                $file_path = $destinationFolder . $filename;

                $imgs[] = $file_path;
            }
            return $imgs;
        } else {
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

            // Resize the image to 90% of its original size
            $image->resize($newWidth, $newHeight, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path($destinationFolder . $filename), 90);

            $file_path = $destinationFolder . $filename;
            return $file_path;
        }
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
