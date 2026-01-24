<?php

namespace App\CPU;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageManager
{

    public static function uploadFile(string $dir, $file = null)
    {
        if ($file != null) {
            $fileName = Carbon::now()->toDateString() . "-" . uniqid() . "." . $file->getClientOriginalExtension();
            if (!Storage::disk('public')->exists($dir)) {
                Storage::disk('public')->makeDirectory($dir);
            }

            $file->storeAs($dir, $fileName, 'public');
        } else {
            $fileName = null;
        }

        return $fileName;
    }
    // public static function upload(string $dir, string $format, $image = null, $alt_text = null)
    // {
    //     if ($image != null) {
    //         if ($alt_text) {
    //             $imageName = Str::slug($alt_text) . "." . $format;
    //         } else {
    //             $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . $format;
    //         }
    //         $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . $format;
    //         if (!Storage::disk('public')->exists($dir)) {
    //             Storage::disk('public')->makeDirectory($dir);
    //         }
    //         //Storage::disk('public')->put($dir . $imageName, file_get_contents($image));
    //         $image->storeAs($dir, $imageName, 'public');
    //     } else {
    //         $imageName = null;
    //     }

    //     return $imageName;
    // }

    public static function upload($path, $image, $alt_text = null)
    {
        if (!$image) {
            return null;
        }

        $ext = $image->getClientOriginalExtension();

        $imageName = $alt_text
            ? Str::slug($alt_text) . '-' . time() . '.' . $ext
            : Carbon::now()->toDateString() . '-' . uniqid() . '.' . $ext;

        $destinationPath = public_path('assets/images/' . $path);

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        $image->move($destinationPath, $imageName);

        return $imageName;
    }
    public static function update(string $dir, $old_image, string $format, $image = null, $alt_text = null)
    {
        if (Storage::disk('public')->exists($dir . $old_image)) {
            Storage::disk('public')->delete($dir . $old_image);
        }
        $imageName = ImageManager::upload($dir, $format, $image, $alt_text);
        return $imageName;
    }

    public static function delete($full_path)
    {
        if (Storage::disk('public')->exists($full_path)) {
            Storage::disk('public')->delete($full_path);
        }

        return [
            'success' => 1,
            'message' => 'Removed successfully !'
        ];
    }
}
