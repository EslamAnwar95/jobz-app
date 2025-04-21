<?php

namespace App\Traits;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

trait HandlesQueuedFileUpload
{
    
    public function moveToPublicTemp($file, string $folder = 'uploads/tmp'): string
    {
        $filename = uniqid() . '_' . $file->getClientOriginalName();
        $file->move(storage_path("app/public/{$folder}"), $filename);

        return "{$folder}/{$filename}";
    }

    /**
     * Move file from temp folder to final destination
     */
    public function moveToFinalLocation(string $tempPath, string $finalFolder): string
    {
        $absolutePath = storage_path("app/public/{$tempPath}");

        if (!file_exists($absolutePath)) {
            throw new \Exception("File not found at: {$absolutePath}");
        }

        $file = new File($absolutePath);
        $filename = basename($absolutePath);

        $finalPath = Storage::disk('public')->putFileAs($finalFolder, $file, $filename);

        // Clean up temp file
        @unlink($absolutePath);

        return $finalPath;
    }
}