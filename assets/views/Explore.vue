<template>
    <div>
        <h1>Explorer</h1>
        <section>
            <h2>Top albums </h2>
            <div class="slider">
                <div v-for="playlist in store.playlists" class="cover-container">
                    <router-link :to="{ name: 'playlist', params: {'id': playlist.id} }">
                        <img :src="'../albums/'+playlist.title+'/'+playlist.image" alt="">
                        <p>{{ playlist.title }}</p>
                        <p class="caption"><a href="#">{{ playlist.artist.name }}</a></p>
                    </router-link>
                </div>
            </div>
        </section>
        <br>
        <section>
            <h2>Top Artistes</h2>
            <div class="slider">
                <div v-for="artist in store.artists" class="cover-container">
                    <router-link :to="{ name: 'artist', params: {'id': artist['id']} }">
                        <img :src="'../artists/'+artist['name']+'/'+artist['image']" alt="">
                        <p>{{ artist['name'] }}</p>
                    </router-link>
                </div>
            </div>
        </section>
    </div>
</template>

<script setup>
import { useDefaultStore } from '../stores/index.js'
const store = useDefaultStore()
</script>