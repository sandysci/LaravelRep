<?php


namespace App\Domain\Utils;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Image;

class ImageUpload
{
    public static function selectedDisk()
    {
        return config('filesystems.default');
    }
    public static function uploadImageAndGetUrl(UploadedFile $image, string $mainDirectory): string
    {
        $fileName = Str::uuid() . time() . '.' . $image->getClientOriginalExtension();

        $file = Storage::disk(config('filesystems.default'))
            ->putFileAs('public/' . $mainDirectory, $image, $fileName, 'public');

        return Storage::disk(config('filesystems.default'))->url($file);
    }

    public static function deleteImageByUrl(string $url): bool
    {
        $baseUrl = rtrim(Storage::disk(self::selectedDisk())->url("/"), '/');

        return Storage::disk(self::selectedDisk())->delete(str_replace($baseUrl, "", $url));
    }

    /**
     * @param Image $image
     * @param string $mainDirectory
     * @return string
     */
    public static function uploadImageAndGetUrlIntervention(Image $image, string $mainDirectory): string
    {
        $fileName = Str::uuid() . time() . '.' . 'jpg';

        $file = Storage::disk(self::selectedDisk())->putFileAs(
            'public/' . $mainDirectory,
            (string)$image->encode('data-url'),
            $fileName,
            'public'
        );

        return Storage::disk(self::selectedDisk())->url($file);
    }
}
