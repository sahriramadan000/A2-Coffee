<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\PersonalAccessToken;

class OneTimeTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Retrieve the token from the Authorization header
        $token = $request->bearerToken();

        if (!$token) {
            Log::warning('Token is missing in request', ['request' => $request->all()]);
            return response()->json(['error' => 'Token is required'], 401);
        }

        // Get the authenticated user using Sanctum's default authentication guard
        // $user = $request->user();
        
        // if (!$user) {
        //     Log::warning('User not authenticated', [
        //         'token' => $token,
        //         'user' => $request->user() // This will likely be null
        //     ]);
        //     return response()->json(['error' => 'User not authenticated'], 401);
        // }

        // Hash the incoming token to match the stored token format
        $tokenRecord = PersonalAccessToken::findToken($token);  

        if (!$tokenRecord) {
            return response()->json(['error' => 'Token not found or invalid'], 401);
        }

        // Check if the token is expired
        if (Carbon::parse($tokenRecord->expires_at)->isPast()) {
            return response()->json(['error' => 'Token has expired'], 401);
        }

        // If the token is valid, proceed to the next request and delete the token
        $tokenRecord->delete(); // Delete the token to make it one-time use

        return $next($request);
    }

}
