<?php

namespace App\Http\Controllers;

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SpotifyLoginController extends Controller
{
    public function redirectToSpotifyProvider()
    {
        return Socialite::driver('spotify')->redirect();
    }

    public function handleSpotifyCallback(Request $request)
    {
        try {
            $user = Socialite::driver('spotify')->user();
            $existingUser = User::where('spotify_id', $user->id)->first();

            if ($existingUser) {
                // Log the existing user in
                Auth::login($existingUser);
                return redirect()->intended('/');
            } else {
                // Create a new user and store their Spotify data
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'spotify_id' => $user->id,
                ]);

                // Associate the Spotify account with the newly created user
                $newUser->spotify()->associate($user->id)->save();

                // Log the new user in
                Auth::login($newUser);
                return redirect()->intended('/');
            }
        } catch (\Exception $e) {
            return redirect()->route('login');
        }
    }
}