import { createRouter, createWebHistory } from 'vue-router'
import Explore from '../views/Explore.vue'
import Search from '../views/Search.vue'
import Loved from '../views/Loved.vue'
import Playlist from '../views/Playlist.vue'
import Artist from '../views/Artist.vue'

const routes = [
  // À compléter
  {
    path: "/explore",
    name: "homepage",
    component: Explore,
  },
  {
    path: "/search",
    name: "search",
    component: Search,
  },
  {
    path: "/loved",
    name: "loved",
    component: Loved,
  },
  {
    path: "/playlist/:id",
    name: "playlist",
    component: Playlist,
  },
  {
    path: "/artist/:id",
    name: "artist",
    component: Artist,
  },
  // {
  //   path: "/parameters",
  //   name: "parameters",
  //   component: {
  //     template: "<div>test</div>",
  //   },
  // },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
  })

export default router
