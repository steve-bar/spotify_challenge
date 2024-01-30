<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spotify;
use App\Models\User;

class SpotifyPlaylistController extends Controller
{
    public function getPlaylist(Request $request)
    {
        $accessToken = $request->user()->spotifyAccessToken;
        $spotify = new Spotify($accessToken);

        $playlists = $spotify->getUserPlaylists();

        // Store the playlists in the database with a timestamp
        Playlist::query()->delete();
        foreach ($playlists as $playlist) {
            Playlist::create([
                'user_id' => $request->user()->id,
                'spotify_id' => $playlist->id,
                'name' => $playlist->name,
                'uri' => $playlist->uri,
                'last_modified' => now(),
            ]);
        }

        return response()->json($playlists);
    }
}
