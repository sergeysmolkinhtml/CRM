<?php

namespace App\Helpers;

use App\Models\FileStorage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

/**
 * Class Reply
 * @package App\Classes
 */
class Files
{

    /**
     * @param $image
     * @param $dir
     * @param null $width
     * @param int $height
     * @param bool $crop
     * @return string
     * @throws \Exception
     */

    public static function upload($image, $dir, $width = null, int $height = 800, bool $crop = false): string
    {
        config(['filesystems.default' => 'local']);

        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $image;
        $folder = $dir . '/';

        if (!$uploadedFile->isValid()) {
            throw new \Exception('File was not uploaded correctly');
        }

        $newName = self::generateNewFileName($uploadedFile->getClientOriginalName());

        $tempPath = public_path('user-uploads/temp/' . $newName);

        /** Check if folder exits or not. If not then create the folder */
        if (!\File::exists(public_path('user-uploads/' . $folder))) {
            \File::makeDirectory(public_path('user-uploads/' . $folder), 0775, true);
        }

        $newPath = $folder . '/' . $newName;

        /** @var UploadedFile $uploadedFile */
        $uploadedFile->storeAs('temp', $newName);

        if (!empty($crop)) {
            // Crop image
            if (isset($crop[0])) {
                // To store the multiple images for the copped ones
                foreach ($crop as $cropped) {
                    $image = Image::make($tempPath);

                    if (isset($cropped['resize']['width']) && isset($cropped['resize']['height'])) {

                        $image->crop(floor($cropped['width']), floor($cropped['height']), floor($cropped['x']), floor($cropped['y']));

                        $fileName = str_replace('.', '_' . $cropped['resize']['width'] . 'x' . $cropped['resize']['height'] . '.', $newName);
                        $tempPathCropped = public_path('user-uploads/temp') . '/' . $fileName;
                        $newPathCropped = $folder . '/' . $fileName;

                        // Resize in Proper format
                        $image->resize($cropped['resize']['width'], $cropped['resize']['height'], function ($constraint) {
                            //$constraint->aspectRatio();
                            // $constraint->upsize();
                        });

                        $image->save($tempPathCropped);

                        \Storage::put($newPathCropped, \File::get($tempPathCropped), ['public']);

                        // Deleting cropped temp file
                        \File::delete($tempPathCropped);
                    }

                }
            } else {
                $image = Image::make($tempPath);
                $image->crop(floor($crop['width']), floor($crop['height']), floor($crop['x']), floor($crop['y']));
                $image->save();
            }

        }
        // Do not compress if the gif is uploaded
        if (($width || $height) && \File::extension($uploadedFile->getClientOriginalName()) !=='gif') {
            // Crop image

            $image = Image::make($tempPath);
            $image->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $image->save();
        }

        \Storage::put($newPath, \File::get($tempPath), ['public']);

        // Deleting temp file
        \File::delete($tempPath);


        return $newName;
    }

    public static function generateNewFileName($currentFileName)
    {
        $ext = strtolower(\File::extension($currentFileName));
        $newName = md5(microtime());

        if ($ext === '') {
            return $newName;
        }

        return $newName . '.' . $ext;
    }

    public static function uploadLocalOrS3($uploadedFile, $dir)
    {
        if (!$uploadedFile->isValid()) {
            throw new \Exception('File was not uploaded correctly');
        }

        if(config('filesystems.default') === 'local'){
            $fileName =  self::upload($uploadedFile,$dir,false,false,false);

            self::storeSize($uploadedFile,$dir,$fileName);

            return  $fileName;
        }

        $newName = self::generateNewFileName($uploadedFile->getClientOriginalName());

        self::storeSize($uploadedFile,$dir,$newName);

        Storage::disk('s3')->putFileAs($dir, $uploadedFile, $newName, 'public');
        return $newName;
    }

    private static function storeSize($uploadedFile,$dir,$fileName){
        FileStorage::create(
            [
                'name' => $fileName,
                'path' => $dir,
                'type' => $uploadedFile->getMimeType(),
                'size' => $uploadedFile->getSize(),
            ]
        );
    }

    public static function deleteFile($image, $folder)
    {
        $dir = trim($folder, '/');
        $path = $dir . '/' . $image;

        if (!\File::exists(public_path($path))) {
            \Storage::delete($path);
        }

        try {
            session()->forget('company_setting');
            session()->forget('company');
            FileStorage::where('name', $image)->delete();
        } catch (\Exception $e) {
            //
        }

        return true;
    }

    public static function deleteDirectory($folder)
    {
        $dir = trim($folder);
        \Storage::deleteDirectory($dir);
        return true;
    }

    public static function uploadToDisk(UploadedFile $file, $path, $disk = 'local')
    {
        if ($disk == 'local'){
            $fileName = self::upload($file, $path, false, false, false);
        }else{
            if (!$file->isValid()) {
                throw new \Exception('File was not uploaded correctly');
            }

            $fileName = self::generateNewFileName($file->getClientOriginalName());

            Storage::disk($disk)->putFileAs($path, $file, $fileName);
        }
        return $fileName;
    }

}
