<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Storage;

/**
 *
 */
trait FileDeleting
{
    /**
     * @param $filePath
     * @return void
     */
    public function deleteFile($filePath)
    {
        if (!empty($filePath) && Storage::disk('local')->exists($filePath)) {
            Storage::disk('local')->delete($filePath);
        }
    }
}
