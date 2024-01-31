<?php

namespace App\Providers;

use App\Actions\Jetstream\DeleteUser;
use Illuminate\Support\ServiceProvider;
use Laravel\Jetstream\Jetstream;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Fortify\Fortify;
use Illuminate\Foundation\Application;
use App\Http\Controllers\SpotifyLoginController;

class JetstreamServiceProvider extends ServiceProvider
{
    protected $spotifyLoginController;

    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->spotifyLoginController = $this->app->make(SpotifyLoginController::class);
    }

    public function register()
    {
        $this->app->singleton(SpotifyLoginController::class, function ($app) {
        return new SpotifyLoginController();
        });
    }

    public function login(Request $request)
    {
        try {
            if ($request->has('spotify')) {
                // Handle Spotify login
                $user = $this->spotifyLoginController->handleSpotifyCallback($request);

                // Log the user in
                auth()->login($user);

                return redirect()->intended('/spotify.display.playlists');
            } else {
                // Handle email and password login
                $credentials = $request->only(['email', 'password']);

                if (! $token = $this->guard()->attempt($credentials)) {
                    return response()->json(['message' => 'Incorrect credentials.'], 401);
                }

                return response()->json(['accessToken' => $token]);
            }
        } catch (Exception $e) {
            if ($e->getCode() === 10500) {
                // Spotify login failed
                return response()->json(['message' => 'Incorrect credentials.'], 401);
            } else {
                // Handle other errors
                throw new Exception('Failed to log in.');
            }
        }
    }
}
