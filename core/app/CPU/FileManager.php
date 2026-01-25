<?php

namespace App\CPU;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Encoders\WebpEncoder;

class FileManager
{

    public static function uploadFile(
        string $dir,
        int $targetSizeKB,
        $image = null,
        $alt_text = null
    ) {
        if (!$image) return null;

        $fullPath = public_path($dir);

        if (!is_dir($fullPath)) {
            mkdir($fullPath, 0777, true);
        }

        $imageName = $alt_text
            ? Str::slug($alt_text) . '.webp'
            : Carbon::now()->format('YmdHis') . '-' . uniqid() . '.webp';

        $manager = new ImageManager(
            new \Intervention\Image\Drivers\Gd\Driver()
        );

        $img = $manager->read($image->getRealPath());

        $quality = 80;
        $temp = tempnam(sys_get_temp_dir(), 'img_');

        do {
            $encoded = $img->encode(new WebpEncoder(quality: $quality));
            file_put_contents($temp, (string)$encoded);
            $size = filesize($temp);
            $quality -= 5;
        } while ($size > ($targetSizeKB * 1024) && $quality > 20);

        // Save directly to assets/storage
        file_put_contents($fullPath . $imageName, file_get_contents($temp));

        @unlink($temp);

        return $imageName;
    }
    public static function updateWithCompress(
        string $dir,
        $old_image,
        $image = null,
        $alt_text = null
    ) {
        $fullPath = public_path($dir);

        if ($old_image && file_exists($fullPath . $old_image)) {
            @unlink($fullPath . $old_image);
        }

        return self::uploadFile($dir, 300, $image, $alt_text);
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
