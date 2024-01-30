<table class="playlist-table">
  <thead>
    <tr>
      <th>Playlist Name</th>
      <th>Playlist URI</th>
    </tr>
  </thead>
  <tbody>
    <tr v-for="playlist in playlists" :key="playlist.id">
      <td>{{ playlist.name }}</td>
      <td>{{ playlist.uri }}</td>
    </tr>
  </tbody>
</table>

