<template>
    <h1>Loved Tracks <span class="material-icons">local_fire_department</span></h1>
    <div class="playlist">
        <Playlist :songs="store.loved_tracks" @music_changed="updateSongEvent" />
    </div>
</template>

<script setup>
import { ref, onMounted, defineProps } from 'vue'
import Playlist from '../components/Playlist.vue'
import { useDefaultStore } from '../stores/index.js'
const store = useDefaultStore()

const props = defineProps(['loved', 'song',])
const emit = defineEmits(['music_changed', 'loved_changed'])

let song = ref(props.loved)

function updateSongEvent(song) {
    emit('music_changed', song)
}

</script>