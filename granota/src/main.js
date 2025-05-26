import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import './assets/tailwind.css'
import 'leaflet/dist/leaflet.css'

import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faEye, faTimes } from '@fortawesome/free-solid-svg-icons'

import Toast from 'vue-toastification'
import 'vue-toastification/dist/index.css'

library.add(faEye, faTimes)

const app = createApp(App)

app.component('font-awesome-icon', FontAwesomeIcon)

app.use(Toast, {
  position: 'top-right',
  timeout: 3000,
  closeOnClick: true,
  pauseOnHover: true,
  draggable: true,
})

app.use(store)
app.use(router)

store.dispatch('auth/initAuth').then(()=>{
    app.mount('#app')
})
