<template>
        <div class="playlist">
            <table class="songs">
                <thead>
                    <tr>
                        <th>
                            #
                        </th>
                        <th>
                            Titre
                        </th>
                        <th>
                            Favoris
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(song, index) in props.songs" :key="index">
                        <td>
                            {{ index+1 }}
                        </td>
                        <td @click="updateSongEvent(song)" style="cursor: pointer">
                            {{ song.title }}
                            <span v-if="song.id == store.current_song['id']" class="material-icons" style="vertical-align: middle; margin-left: 1em;">volume_up</span>
                        </td>
                        <td>
                            <span class="material-icons" @click="editLoved(song)" :class="{'hidden': !store.loved_tracks.find(_song => _song.id == song.id)}">local_fire_department</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
</template>

<script setup>
import { useDefaultStore } from '../stores/index.js'
import { defineEmits } from 'vue'

const store = useDefaultStore()
const props = defineProps(['songs'])
const emit = defineEmits(['music_changed'])

function updateSongEvent(song) {
    emit('music_changed', song)
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
</script>