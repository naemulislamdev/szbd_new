<?php

namespace App\CPU;

use Carbon\Carbon;
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
        if (!$image instanceof \Illuminate\Http\UploadedFile) {
            return null;
        }

        if (!$image->isValid()) {
            return null;
        }
        if (!str_starts_with($image->getMimeType(), 'image/')) {
            return null;
        }

        $fullPath = $dir;

        if (!is_dir($fullPath)) {
            mkdir($fullPath, 0777, true);
        }

        $imageName = $alt_text
            ? Str::slug($alt_text) . '.webp'
            : Carbon::now()->format('YmdHis') . '-' . uniqid() . '.webp';

        $manager = new ImageManager(
            new \Intervention\Image\Drivers\Gd\Driver()
        );

        $img = $manager->read($image->getPathname());


        $quality = 80;
        $temp = tempnam(sys_get_temp_dir(), 'img_');


        do {
            $encoded = $img->encode(new WebpEncoder(quality: $quality));
            file_put_contents($temp, (string) $encoded);
            $size = filesize($temp);
            $quality -= 5;
        } while ($size > ($targetSizeKB * 1024) && $quality > 20);

        file_put_contents($fullPath . $imageName, file_get_contents($temp));
        @unlink($temp);

        return $imageName;
    }

    public static function updateFile(
        string $dir,
        $old_image,
        $image = null,
        $alt_text = null
    ) {
        $fullPath = $dir;

        if ($old_image && file_exists($fullPath . $old_image)) {
            @unlink($fullPath . $old_image);
        }

        return self::uploadFile($dir, 300, $image, $alt_text);
    }

    public static function delete($full_path)
    {
        if (file_exists($full_path)) {
            @unlink($full_path);
        }
        return [
            'success' => 1,
            'message' => 'Removed successfully !'
        ];
    }
}
