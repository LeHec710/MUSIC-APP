<template>
    <div class="player">
        <h2 v-if="store.current_song == 'Joue une musique'">{{store.current_song}}</h2>
        <div v-else>
            <h2>{{ store.current_song['title'] + ' - ' + store.current_song['Artist']['name'] }} <span class="material-icons" :class="{'hidden': !store.loved_tracks.find(song => song.id == store.current_song.id)}" @click="editLoved(store.current_song)">local_fire_department</span></h2>
            
            <div class="controls">
                <span class="material-icons">navigate_before</span>
                <span class="material-icons" @click="play_pause(store.current_song)">{{ play_icon }}</span>
                <span class="material-icons">navigate_next</span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useDefaultStore } from '../stores/index.js'
import { ref, watch } from 'vue';

const store = useDefaultStore()
const props = defineProps(['song'])

let audio = ref(new Audio())
let play_icon = ref('play_arrow')
let is_playing = false
let playing_song

function play(song) {
    store.current_song = store.songs.find(_song => _song.id == song['id'])

    if(song != playing_song) {
        const audio_path = '../albums/' + store.current_song['playlist'][0]['title'] + '/' + song.song
        audio.value.src = audio_path
        audio.value.currentTime = 0
    }

    audio.value.play()
    playing_song = song
    play_icon.value = 'pause'
    is_playing = true
}

function pause() {
    audio.value.pause()
    playing_song = null
    play_icon.value = 'play_arrow'
    is_playing = false
}

function play_pause() {
    if(is_playing) {
        play_icon.value = 'play_arrow'
        pause()
    } else {
        play_icon.value = 'pause'
        play(props.song)
    }
}

function editLoved(song) {
    if(store.loved_tracks.find(_song => _song['id'] == song['id'])) {
        const index = store.loved_tracks.indexOf(store.loved_tracks.find(_song => _song['id'] == song['id']))
        if (index > -1) {
            store.loved_tracks.splice(index, 1)
        }
    } else {
        store.loved_tracks.push(song)
    }
} 

watch(() => props.song, song => {
    play(song)
});
</script>