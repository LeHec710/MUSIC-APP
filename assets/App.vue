<script setup>
import Nav from './components/Nav.vue'
import Player from './components/Player.vue'

import Axios from 'axios'
import { onMounted, ref } from 'vue'
import { useDefaultStore } from './stores/index'
import { storeToRefs } from 'pinia'

const store = useDefaultStore()
const current_song = ref(store.current_song)
const is_loading = ref()
onMounted(() => {
  store.loadPlaylists()
  store.loadArtists()
  store.loadSongs()
  
    Axios.interceptors.request.use(
      (config) => {
        is_loading.value = true;
        return config;
      },
      (error) => {
        is_loading.value = false;
        return Promise.reject(error);
      }
    );

    Axios.interceptors.response.use(
      (response) => {
        is_loading.value = false;
        return response;
      },
      (error) => {
        is_loading.value = false;
        return Promise.reject(error);
      }
    );
})

function change_music(song) {
  current_song.value = song
}


</script>

<template>
    <main>
        <Player :song="current_song" />
        <router-view :class="{loading: is_loading}" @music_changed="change_music" />
        <div v-if="is_loading" class="lds-ring"><div></div><div></div><div></div><div></div></div>
        <Nav />
    </main>
</template>

<style>
  .loading {
    display: none;
  }
    
  /* loader */
  .lds-ring {
    display: inline-block;
    position: fixed;
    top: 40%;
    left: 50%;
    transform: translate(-50%);
    width: 80px;
    height: 80px;
  }
  .lds-ring div {
    box-sizing: border-box;
    display: block;
    position: absolute;
    width: 64px;
    height: 64px;
    margin: 8px;
    border: 8px solid #ff8199;
    border-radius: 50%;
    animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
    border-color: #ff8199 transparent transparent transparent;
  }
  .lds-ring div:nth-child(1) {
    animation-delay: -0.45s;
  }
  .lds-ring div:nth-child(2) {
    animation-delay: -0.3s;
  }
  .lds-ring div:nth-child(3) {
    animation-delay: -0.15s;
  }
  @keyframes lds-ring {
    0% {
      transform: rotate(0deg);
    }
    100% {
      transform: rotate(360deg);
    }
  }

</style>
