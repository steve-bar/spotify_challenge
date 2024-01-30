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

        $playlistId = $request->input('playlistId');

        try {
            $playlist = $spotify->getPlaylist($playlistId);
        } catch (SpotifyException $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }

        return response()->json($playlist);
    }
}
