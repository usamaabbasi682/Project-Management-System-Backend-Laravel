<?php

namespace App\Services;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use function md5;
use function microtime;

class FileUploadService
{
    public static function upload(UploadedFile $file, $path = 'files')
    {
        try {

            $name = md5(microtime()).'.'.$file->getClientOriginalExtension();

            $file->storeAs($path, $name);
            $file->uploaded_name = $name;
            $file->uploaded_path = str_replace('/public', '', $path);

            return $file;

        } catch (Exception $exception) {

            return null;
        }
    }

    public static function delete($path = 'files/')
    {
        try {

            if (Storage::exists($path)) {
                Storage::delete($path);
            }
            return true;

        } catch (Exception $exception) {

            return null;

        }
    }
}