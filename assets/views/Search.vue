<template>
    <div>
        <h1>Rechercher</h1>
        <div class="search">
            <input type="text" class="search" v-model="search" autofocus placeholder="Recherche artiste/album/musique">
            <input type="submit" class="material-icons search-icon" value="search">
        </div>
        <br><br><br>
        <section v-if="artists != false">
            <h3>Artistes</h3>
            <div class="slider">
                <div class="cover-container" v-for="artist in artists">
                    <router-link :to="{ name: 'artist', params: { id: artist.id } }">
                        <img :src="'../artists/' + artist.name + '/' + artist.image">
                        <p>{{artist.name}}</p>
                    </router-link>
                </div>
            </div>
        </section>
        <br><br><br>
        <section v-if="albums != false">
            <h3>Albums</h3>
            <div class="slider">
                <div class="cover-container" v-for="album in albums">
                    <router-link :to="{ name: 'playlist', params: { id: album.id } }">
                        <img :src="'../albums/' + album.title + '/' + album.image">
                        <p>{{album.title}}</p>
                        <p class="caption">{{ album['artist']['name'] }}</p>
                    </router-link>
                </div>
            </div>
        </section>
        <section v-if="songs != false">
            <h3>Musiques</h3>
            <Playlist :songs="songs" @music_changed="updateSongEvent"/>
        </section>

    </div>
</template>

<script setup>
import Playlist from '../components/Playlist.vue'
import {ref, watch} from 'vue'
import { useDefaultStore } from '../stores/index.js'

const store = useDefaultStore()
const emit = defineEmits(['music_changed'])

let search = ref()
let songs = ref(false)
let albums = ref(false)
let artists= ref(false)

console.log(search.value)
function updateSongEvent(song) {
    emit('music_changed', song)
}

watch(search, (value) => {
    if(value == '') {
        albums.value = false
        songs.value = false
        artists.value = false
    } else {
        albums.value = store.getAlbumsByName(value)
        songs.value = store.getSongsByName(value)
        artists.value = store.getArtistsByName(value)
    }
});

</script>