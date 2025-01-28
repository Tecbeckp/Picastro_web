<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Http;

trait  UploadImageTrait
{
    public function originalImageUpload($file, $destinationFolder, $chatImage = false, $flip_vertically = false, $flip_horizontally = false, $rotation_angle = false)
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
        } else {
            $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.webp';
            $filePath = $destinationFolder . time() . '-' . $fileName;
            // Read the image using Intervention Image
            $image = Image::read($file);

            if ($rotation_angle) {
                $rotation_angle = (float)$rotation_angle;
                $image->rotate($rotation_angle); // You can change the degree as needed
            }

            if ($flip_vertically == 'true') {
                $image->flip('v');
            }

            if ($flip_horizontally == 'true') {
                $image->flip('h');
            }

            // Save the processed image to a temporary file
            $tempFilePath = tempnam(sys_get_temp_dir(), 'image') . '.webp';
            $image->save($tempFilePath, 100, 'webp', ['lossless' => true]);

            // Upload the processed image to S3
            Storage::disk('s3')->put($filePath, file_get_contents($tempFilePath));

            // Get the URL of the uploaded image
            $fileUrl = Storage::disk('s3')->url($filePath);

            // Clean up the temporary file
            unlink($tempFilePath);
            return $fileUrl;
        }
    }

    public function imageUpload($file, $destinationFolder, $flip_vertically = false, $flip_horizontally = false, $rotation_angle = false)
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

        // Resize the image to 90% of its original size
        $image->resize($newWidth, $newHeight, function ($constraint) {
            $constraint->aspectRatio();
        })->save(public_path($destinationFolder . $filename), 90);

        if ($rotation_angle) {
            $rotation_angle = (float)$rotation_angle;
            $image->rotate($rotation_angle); // You can change the degree as needed
        }
        if ($flip_vertically == 'true') {
            $image->flip('v');
        }

        if ($flip_horizontally == 'true') {
            $image->flip('h');
        }

        // Save the image
        $image->save(public_path($destinationFolder . $filename), 90);

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
    private function removeBaseUrl(?string $path): ?string
    {
        $baseUrl = url('/public'); // Your base URL
        return $path ? str_replace($baseUrl . '/', '', $path) : null;
    }
}
