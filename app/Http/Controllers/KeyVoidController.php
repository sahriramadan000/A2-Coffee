<?php

namespace App\Http\Controllers;

use App\Models\KeyVoid;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class KeyVoidController extends Controller
{
    public function generateKey(Request $request)
    {
        $key = Str::random(5);

        // Ensure only one row exists in the key_voids table
        $keyVoid = KeyVoid::first();

        if ($keyVoid) {
            // Update the existing record
            $keyVoid->update(['key' => $key]);
        } else {
            // Create a new record if none exists
            $keyVoid = KeyVoid::create(['key' => $key]);
        }

        return response()->json(['key' => $keyVoid->key]);
    }
}
