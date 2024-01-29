<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class SpotifyController extends Controller
{
    use AuthenticatesUsers;

    public function redirectToSpotifyProvider()
    {
        return Socialite::driver('spotify')->redirect();
    }

    public function handleSpotifyCallback()
    {
        try {
            $user = Socialite::driver('spotify')->user();

            auth()->login($user, true);

            return redirect()->intended('dashboard');
        } catch (\Exception $e) {
            return redirect()->route('login');
        }
    }
}