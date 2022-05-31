<template>
    <div class="playlist" v-if="Object.keys(store.playlist).length != 0">
        <div class="playlist-description">
            <img :src="'../albums/' + store.playlist['title'] + '/' + store.playlist['image']">
            <div class="playlist-description-infos">
                <h1>{{ store.playlist["title"] }}</h1>
                <router-link :to="{ name: 'artist', params: { id: store.playlist['artist']['id'] } }">
                    <div class="artist">
                        <img :src="'../artists/'+ store.playlist['artist']['name'] + '/' + store.playlist['artist']['image'] ">
                        <h3>{{ store.playlist['artist']['name'] }}</h3>
                    </div>
                </router-link>
                <p class="caption"><b>{{ Object.keys(store.playlist).length }} </b> musiques</p>
                <p class="caption">Sorti le <b>{{ format_date(store.playlist['date']) }}</b></p>
            </div>
        </div>
        <Playlist :songs="store.playlist['songs']" @music_changed="updateSongEvent" />
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
const playlist = ref(store.changePath("playlist", "/api/playlists/" + routeId.value))
const emit = defineEmits(['music_changed'])

function updateSongEvent(song) {
    emit('music_changed', song)
}

function format_date(value){
    if (value) {
        return moment(String(value)).format("Do MMM YYYY")
    }
}

</script>