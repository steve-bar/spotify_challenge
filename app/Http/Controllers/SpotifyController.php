<?php

namespace App\Http\Controllers;

use Aerni\Spotify\SpotifyClient;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use App\Models\User;

class SpotifyController extends Controller
{
    public function redirectToSpotifyProvider()
    {
        return Socialite::driver('spotify')->redirect();
    }

    public function handleSpotifyCallback(Request $request)
    {
        try {
            $user = Socialite::driver('spotify')->user();

            // Check if the user already exists
            $existingUser = User::where('spotify_id', $user->id)->first();

            if ($existingUser) {
                // Log the existing user in
                auth()->login($existingUser);

                return redirect()->intended('/');
            } else {
                // Create a new user and store their Spotify data
                $newUser = User::create([
                    'spotify_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar' => $user->avatar[0]['url'],
                ]);

                // Log the new user in
                auth()->login($newUser);

                return redirect()->intended('/');
            }
        } catch (\Exception $e) {
            return redirect()->route('login');
        }
    }
}