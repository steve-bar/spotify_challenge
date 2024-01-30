<?php

namespace App\Http\Middleware;

use App\Models\SpotifyUser;
use Illuminate\Http\Request;
use Closure;

class AuthenticateSpotifyUser
{
    public function handle(Request $request, Closure $next)
    {
        $accessToken = $request->bearerToken();

        if (!$accessToken) {
            return response()->json([
                'error' => 'Missing Spotify access token'
            ], 401);
        }

        try {
            $spotifyUser = SpotifyUser::where('accessToken', $accessToken)->first();
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Invalid Spotify access token'
            ], 401);
        }

        if (!$spotifyUser) {
            return response()->json([
                'error' => 'User not found'
            ], 401);
        }

        $request->user()->setSpotifyUser($spotifyUser);

        return $next($request);
    }
}

