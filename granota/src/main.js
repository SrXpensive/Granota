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

import VueSweetalert2 from 'vue-sweetalert2'
import 'sweetalert2/dist/sweetalert2.min.css'

library.add(faEye, faTimes)

const app = createApp(App)

app.component('font-awesome-icon', FontAwesomeIcon)

app.use(Toast, {
  position: 'top-right',
  timeout: 3000,
  closeOnClick: true,
  pauseOnHover: true,
  draggable: false,
})

app.use(store)

app.use(VueSweetalert2)

store.dispatch('auth/initAuth')
  .catch((error)=>{
    console.error('Error en initAuth: ', error)
  })
  .finally(()=>{
    app.use(router)
    app.mount('#app') 
})
