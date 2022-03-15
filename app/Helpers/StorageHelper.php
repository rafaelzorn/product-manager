<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class StorageHelper
{
    /**
     * @param string $filePath
     *
     * @return void
     */
    public static function delete(string $filePath): void
    {
        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
        }
    }
}
