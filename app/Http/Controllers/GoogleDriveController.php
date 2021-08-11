<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GoogleDriveController extends Controller
{
    static function put($fileNameWithPath, $content)
    {
        Storage::cloud()->put($fileNameWithPath, $content);
        return 'File was saved to Google Drive';

    }

    static function putExisting($fileNameWithPath, $fileData)
    {
        Storage::cloud()->put($fileNameWithPath, $fileData);
        return 'File was saved to Google Drive';
    }

    static function listFiles($dir = '/', $recursive = false)
    {
        // Get subdirectories also?
        $contents = collect(Storage::cloud()->listContents($dir, $recursive));

        //return $contents->where('type', '=', 'dir'); // directories
        return $contents->where('type', '=', 'file'); // files

    }

    static function listDirs($dir = '/', $recursive = false)
    {
        // Get subdirectories also?
        $contents = collect(Storage::cloud()->listContents($dir, $recursive));

        return $contents->where('type', '=', 'dir'); // directories
    }

    static function listFolderContents($folder = '/', $recursive = false)
    {
        $contents = collect(Storage::cloud()->listContents('/', $recursive));

        // Find the folder you are looking for...
        $dir = $contents->where('type', '=', 'dir')
            ->where('filename', '=', $folder)
            ->first(); // There could be duplicate directory names!

        if (!$dir) {
            return 'No such folder!';
        }

        // Get the files inside the folder...
        $files = collect(Storage::cloud()->listContents($dir['path'], false))
            ->where('type', '=', 'file');

        return $files->mapWithKeys(function ($file) {
            $filename = $file['filename'] . '.' . $file['extension'];
            $path = $file['path'];

            // Use the path to download each file via a generated link..
            // Storage::cloud()->get($file['path']);

            return [$filename => $path];
        });
    }

    static function findFolder($folder, $recursive = false, $searchIn = '/')
    {
        $contents = collect(Storage::cloud()->listContents($searchIn, $recursive));

        // Find the folder you are looking for...
        $dir = $contents->where('type', '=', 'dir')
            ->where('filename', '=', $folder)
            ->first(); // There could be duplicate directory names!

        if (!$dir) {
            return null;
        }
        return $dir;
    }

    static function listFolderContentsDirectories($folder = '/', $recursive = false)
    {

        $dir = GoogleDriveController::findFolder($folder, $recursive);

        // Get the files inside the folder...
        $files = collect(Storage::cloud()->listContents($dir['path'], false))
            ->where('type', '=', 'dir');

        return $files->mapWithKeys(function ($file) {
            $filename = $file['filename'] . '.' . $file['extension'];
            $path = $file['path'];

            // Use the path to download each file via a generated link..
            // Storage::cloud()->get($file['path']);

            return [$filename => $path];
        });
    }

    static function get($filename, $dir = '/', $recursive = false, $store = true, $dirToSave = 'xlsx', $inArray = false, $inRawData = false)
    {
        $contents = collect(Storage::cloud()->listContents($dir, $recursive));

        $file = $contents
            ->where('type', '=', 'file')
            ->where('filename', '=', pathinfo($filename, PATHINFO_FILENAME))
            ->where('extension', '=', pathinfo($filename, PATHINFO_EXTENSION))
            ->first(); // there can be duplicate file names!

        if ($store) {
            $readStream = Storage::cloud()->getDriver()->readStream($file['path']);
            $targetFile = storage_path("app\\{$dirToSave}\\{$filename}");
            if (file_put_contents($targetFile, stream_get_contents($readStream), FILE_APPEND)) {
                return array_merge(['target_file' => $targetFile], $file);
            }
        }

        if ($inArray) {
            return $file; // array with file info
        }

        $rawData = Storage::cloud()->get($file['path']);

        if ($inRawData) {
            return $rawData; // array with file info
        }

        return response($rawData, 200)
            ->header('ContentType', $file['mimetype'])
            ->header('Content-Disposition', "attachment; filename='$filename'");
    }

    static function putGetStream($filename, $dir = '/', $recursive = false)
    {

        // Use a stream to upload and download larger files
        // to avoid exceeding PHP's memory limit.

        // Thanks to @Arman8852's comment:
        // https://github.com/ivanvermeyen/laravel-google-drive-demo/issues/4#issuecomment-331625531
        // And this excellent explanation from Freek Van der Herten:
        // https://murze.be/2015/07/upload-large-files-to-s3-using-laravel-5/

        // Assume this is a large file...
        $filePath = public_path($filename);

        // Upload using a stream...
        Storage::cloud()->put($filename, fopen($filePath, 'r+'));

        // Get file listing...
        $contents = collect(Storage::cloud()->listContents($dir, $recursive));

        // Get file details...
        $file = $contents
            ->where('type', '=', 'file')
            ->where('filename', '=', pathinfo($filename, PATHINFO_FILENAME))
            ->where('extension', '=', pathinfo($filename, PATHINFO_EXTENSION))
            ->first(); // there can be duplicate file names!

        //return $file; // array with file info

        // Store the file locally...
        //$readStream = Storage::cloud()->getDriver()->readStream($file['path']);
        //$targetFile = storage_path("downloaded-{$filename}");
        //file_put_contents($targetFile, stream_get_contents($readStream), FILE_APPEND);

        // Stream the file to the browser...
        $readStream = Storage::cloud()->getDriver()->readStream($file['path']);

        return response()->stream(function () use ($readStream) {
            fpassthru($readStream);
        }, 200, [
            'Content-Type' => $file['mimetype'],
            //'Content-disposition' => 'attachment; filename="'.$filename.'"', // force download?
        ]);
    }

    static function createDir($newDirName)
    {
        Storage::cloud()->makeDirectory($newDirName);
        return 'Directory was created in Google Drive';
    }

    static function createSubDir($dir, $newDirName, $recursive = false)
    {
        $contents = collect(Storage::cloud()->listContents($dir, $recursive));

        $dir = $contents->where('type', '=', 'dir')
            ->where('filename', '=', 'Test Dir')
            ->first(); // There could be duplicate directory names!

        if (!$dir) {
            return 'Directory does not exist!';
        }

        // Create sub dir
        Storage::cloud()->makeDirectory($dir['path'] . $newDirName);

        return 'Sub Directory was created in Google Drive';
    }

    static function puInDir($filename, $content, $dir = '/', $recursive = false)
    {
        $contents = collect(Storage::cloud()->listContents($dir, $recursive));

        $dir = $contents->where('type', '=', 'dir')
            ->where('filename', '=', 'Test Dir')
            ->first(); // There could be duplicate directory names!

        if (!$dir) {
            return 'Directory does not exist!';
        }

        Storage::cloud()->put($dir['path'] . '/' . $filename, $content);

        return 'File was created in the sub directory in Google Drive';
    }
}
