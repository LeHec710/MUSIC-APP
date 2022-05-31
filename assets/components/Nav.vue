<template>
    <nav class="navbar">
        <ul>
            <li>
                <router-link :to="{ name: 'homepage' }" @click.native="redirect('/explore')">
                        <span class="material-icons">music_note</span>            
                </router-link>
            </li>
            <li>
                <router-link :to="{ name: 'loved' }" @click.native="redirect('/loved')">
                        <span class="material-icons">local_fire_department</span>            
                </router-link>
            </li>
            <li>
                <router-link :to="{ name: 'search' }" @click.native="redirect('/search')">
                    <span class="material-icons">search</span>            
                </router-link>
            </li>
            <li>
                <a href="/parameters" @click.native="redirect('/parameters')">
                    <span class="material-icons">settings</span>            
                </a>
            </li>
        </ul>
    </nav>
</template>

<script setup>

function redirect(link) {
    const links = ['/explore', '/loved', '/search']
    
    links.forEach((link) => {
        if(!window.location.href.includes(link)) {
            document.querySelector("#body").style.display = "none"
        }
    })
}

</script>

<style scoped>
.router-link-active span {
    color: #ff2d55;
}
</style>