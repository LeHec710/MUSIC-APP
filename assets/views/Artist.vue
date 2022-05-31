<template>
    <div class="playlist" v-if="Object.keys(store.artist).length != 0">
        <div class="playlist-description">
            <img :src="'../artists/' + store.artist['name'] + '/' + store.artist['image']">
            <div class="playlist-description-infos">
                <h1>{{ store.artist['name'] }}</h1>
                <p>On peut ajouter une description et des stats de l'artiste ici</p>
            </div>
        </div>
        <section>
            <h2>Albums</h2>
            <div class="slider">
                <div class="cover-container" v-for="album in store.getAlbumsFromArtistId(store.artist['id'])">
                    <router-link :to="{ name: 'playlist', params: { id: album['id'] } }">
                        <img :src="'../albums/' + album['title'] + '/' + album['image']">
                        <p>{{album.title}}</p>
                    </router-link>     
                </div>
            </div>
        </section>
        <br><br>
        <h2>Musiques</h2>
        <Playlist :songs="store.artist['songs']" @music_changed="updateSongEvent" />
    </div>
</template>

<script setup>

import { ref, onMounted, defineEmits } from 'vue'
import { useDefaultStore } from '../stores/index.js'
import { useRoute } from 'vue-router'
import moment from 'moment'
import 'moment/locale/fr'

import Playlist from '../components/Playlist.vue'

const route = useRoute()
const store = useDefaultStore()
moment.locale('fr')

const routeId = ref(route.params.id)
const artist = ref(store.changePath("artist", "/api/artists/" + routeId.value))
const emit = defineEmits(['music_changed'])

function updateSongEvent(song) {
    emit('music_changed', song)
}
</script>