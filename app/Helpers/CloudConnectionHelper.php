<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class CloudConnectionHelper
{
    public static function isConnectedToCloud()
    {
        try {
            // Lakukan ping sederhana ke host cloud
            Http::timeout(5)->get(env('DB_CLOUD_HOST'));
        } catch (\Exception $e) {
            return false;
        }
    }
}
