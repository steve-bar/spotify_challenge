<?php

namespace App\Providers;

use App\Actions\Jetstream\DeleteUser;
use Illuminate\Support\ServiceProvider;
use Laravel\Jetstream\Jetstream;
use Laravel\Fortify\Fortify;

class JetstreamServiceController extends Controller
{
    use RegistersUsers;

    protected $spotifyController;

    public function __construct(SpotifyController $spotifyController)
    {
        $this->spotifyController = $spotifyController;
        $this->middleware('auth:sanctum')->except(['register', 'login']);
    }

    public function register(Request $request)
    {
        try {

            $user = $this->spotifyController->registerWithSpotify($request->input('spotify_id'));

            if ($user) {

                auth()->login($user);
                return redirect()->intended('dashboard');
            }
        } catch (Exception $e) {

            $this->validator($request->all())->validate();

            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ]);

            if ($request->has('avatar')) {
                $user->avatar = $request->file('avatar')->store('avatars');
                $user->save();
            }

            $this->guard()->attempt($request->only('email', 'password'));
            return redirect()->intended('dashboard');
        }
    }

    public function login(Request $request)
    {
        try {
            // Attempt to log in using Spotify credentials
            $user = $this->spotifyController->loginWithSpotify($request->input('spotify_id'));

            if ($user) {
                // If successful, log the user in and redirect
                auth()->login($user);
                return redirect()->intended('dashboard');
            }
        } catch (Exception $e) {
            // If Spotify login fails, handle standard login
            $credentials = $request->only('email', 'password');

            if (! $token = $this->guard()->attempt($credentials)) {
                return response()->json(['message' => 'Incorrect credentials.'], 401);
            }

            return response()->json(['accessToken' => $token]);
        }
    }
}
