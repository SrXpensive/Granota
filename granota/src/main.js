import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import './assets/tailwind.css'
import 'leaflet/dist/leaflet.css'

const app = createApp(App)

store.dispatch('auth/initAuth').then(()=>{
    app.use(store).use(router).mount('#app')
})
