import { defineStore } from 'pinia'
import Axios from 'axios'

export const useDefaultStore = defineStore({
  id: "default",
  state: () => ({
    entity: "",
    current_song: "Joue une musique",
    current_playlist: [],
    loved_tracks: [],

    playlists: [],
    playlist: [],

    artists: [],
    artist: [],

    songs: [],
  }),
  getters: {
    getArtistById: (state) => {
      return (artistId) =>
        state.artists.find((artist) => artist.id == artistId);
    },
    getSongById: (state) => {
      return (songId) => {
        state.songs.find((song) => song.id == songId);
      };
    },
    getPlaylistById: (state) => {
      return (playlistId) => {
        state.playlists.find((playlist) => playlist.id == playlistId);
      };
    },
    getArtistFromSongId: (state) => {
      return (songId) => {
        state.artists.forEach((artist) => {
          artist.songs.forEach((song) => {
            if (song.id == songId) {
              return artist;
            }
          });
        });
      };
    },
    getAlbumsFromArtistId: (state) => {
      return (artistId) =>
        state.playlists.filter(
          (album_id) => album_id["artist"]["id"] == artistId
        );
    },
    getSongsFromArtistId: (state) => {
      return (artistId) =>
        state.songs.filter((song) => song["Artist"]["id"] == artistId);
    },
    getSongsFromPlaylistId: (state) => {
      return (playlistId) =>
        state.songs.filter((song) => song.playlist[0]["id"] == playlistId);
    },

    getSongsByName: (state) => {
      return (songName) => {
        let songs = [];
        state.songs.filter((song) => {
          if (
            song.title.toLowerCase().includes(String(songName).toLowerCase())
          ) {
            songs.push(song);
          }
        });
        return songs;
      };
    },
    getAlbumsByName: (state) => {
      return (albumName) => {
        let albums = [];
        state.playlists.filter((album) => {
          if (
            album.title.toLowerCase().includes(String(albumName).toLowerCase())
          ) {
            albums.push(album);
          }
        });
        return albums;
      };
    },
    getArtistsByName: (state) => {
      return (artistName) => {
        let artists = [];
        state.artists.filter((artist) => {
          if (
            artist.name.toLowerCase().includes(String(artistName).toLowerCase())
          ) {
            artists.push(artist);
          }
        });
        return artists;
      };
    },
  },
  actions: {
    loadPlaylists() {
      Axios.get("/api/playlists")
        .then((response) => response.data)
        .then(
          (playlistsDatas) => (this.playlists = playlistsDatas["hydra:member"])
        );
    },
    loadArtists() {
      Axios.get("/api/artists")
        .then((response) => response.data)
        .then((artistsDatas) => (this.artists = artistsDatas["hydra:member"]));
    },
    loadSongs() {
      Axios.get("/api/songs")
        .then((response) => response.data)
        .then((songsDatas) => {
          this.songs = songsDatas["hydra:member"];
        });
    },
    loadDataEntity(path) {
      if (this.entity == "playlist") {
        Axios.get(path)
          .then((response) => response.data)
          .then((entitiesDatas) => (this.playlist = entitiesDatas));
      }
      if (this.entity == "artist") {
        Axios.get(path)
          .then((response) => response.data)
          .then((entitiesDatas) => (this.artist = entitiesDatas));
      }
    },
    changePath(tab, path) {
      this.entity = tab;
      this.loadDataEntity(path);
    },
  },
});
