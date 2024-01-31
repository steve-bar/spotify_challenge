<?php

namespace Tests\Unit;

use App\Http\Controllers\SpotifyLoginController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
use Tests\TestCase;

class SpotifyLoginControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the redirectToSpotifyProvider method redirects to the Spotify login page
     */
    public function testRedirectToSpotifyProvider()
    {
        $controller = new SpotifyLoginController();

        $response = $controller->redirectToSpotifyProvider();

        $this->assertInstanceOf('Illuminate\Http\RedirectResponse', $response);
        $this->assertEquals('/auth/spotify', $response->headers->get('location'));
    }

    /**
     * Test that the handleSpotifyCallback method logs the existing user in
     */
    public function testHandleSpotifyCallbackExistingUser()
    {
        $existingUser = factory(User::class)->create([
            'spotify_id' => '1234567890',
        ]);

        $spotifyUser = Mockery::mock('Laravel\Socialite\User');
        $spotifyUser->shouldReceive('getId')->andReturn('1234567890');
        $spotifyUser->shouldReceive('getName')->andReturn('Test User');
        $spotifyUser->shouldReceive('getEmail')->andReturn('test.user@example.com');

        $response = $this->actingAs($existingUser)
            ->post('/login/spotify/callback');

        $this->assertTrue(auth()->check());
        $this->assertEquals($existingUser->id, auth()->user()->id);
        $this->assertEquals('/', $response->headers->get('location'));
    }

    /**
     * Test that the handleSpotifyCallback method creates a new user if one doesn't exist
     */
    public function testHandleSpotifyCallbackNewUser()
    {
        $spotifyUser = Mockery::mock('Laravel\Socialite\User');
        $spotifyUser->shouldReceive('getId')->andReturn('9876543210');
        $spotifyUser->shouldReceive('getName')->andReturn('New User');
        $spotifyUser->shouldReceive('getEmail')->andReturn('new.user@example.com');

        $response = $this->post('/login/spotify/callback');

        $this->assertTrue(auth()->check());
        $newUser = User::find(1);
        $this->assertEquals('New User', $newUser->name);
        $this->assertEquals('new.user@example.com', $newUser->email);
        $this->assertEquals('9876543210', $newUser->spotify_id);
        $this->assertEquals('/', $response->headers->get('location'));
    }
}