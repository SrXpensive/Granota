<template>
<div>

    <button @click="showFilters = !showFilters"
        class="fixed top-16 left-4 z-50 bg-green-600 text-white px-3 py-1 rounded shadow hover:bg-green-700 transition">
        {{ showFilters ? 'Ocultar filtre': 'Mostrar filtre'}}
    </button>
    <transition name="slide-filters">
        <div v-if="showFilters" class="w-full bg-green-100 border-b border-green-300 py-3 px-4 shadow-sm z-40">
            <select v-model="selectedType" @change="emitType" class="mt-2 w-64 rounded-xl border border-green-400 bg-green-50 px-4 py-2 text-green-800 shadow-sm transition-all duration-200 ease-in-out focus:border-green-600 focus:outline-none focus:ring-2 focus:ring-green-300 hover:bg-green-100">
            <option value="">Tots els tipus</option>
            <option v-for="type in types" :key="type" :value="type">
                {{ type }}
            </option>
            </select>
        </div>
    </transition>

    <l-map
        style="height: calc(100vh - 5rem); width: 100vw; z-index: 0;" 
        :zoom="15" 
        :center="center" 
        @click="isClickable">
            <l-tile-layer
                :url="tileUrl"
                :attribution="tileAttribution"
                />
            <l-marker
                v-for="marker in markers"
                :key="marker.id"
                :lat-lng="[marker.lat, marker.lng]"
            >
                <l-popup :max-width="350">
                    {{ console.log(marker.id) }}
                    <img
                        v-if="marker.image"
                        :src="`http://localhost:8000/uploads/${marker.image}`"
                        alt="Imagen del marcador"
                        class="mt-2 max-h-96 w-full object-cover rounded"
                    />
                    <strong>{{ marker.title }}</strong><br/>
                    {{console.log(marker.id)}}
                    {{ marker.description }}
                    <div class="mt-4 flex justify-center space-x-6">
                        <font-awesome-icon @click.stop="$emit('viewPost', marker)" icon="eye" class="cursor-pointer hover:text-blue-800 text-blue-600" title="Veure més" style="font-size: 1.8rem;"/>
                    
                        <font-awesome-icon v-if="user && (marker.userId === user.id || user.roles.includes('ROLE_ADMIN'))" 
                         @click.stop="$emit('delete', marker)" icon="times" class="cursor-pointer text-red-600 hover:text-red-800 ml-3" title="Eliminar marcador" style="font-size: 1.8rem;"/>
                    </div>
                </l-popup>
            </l-marker>
        </l-map>
    </div>
</template>

<script>
    import {LMap, LTileLayer, LMarker, LPopup} from "@vue-leaflet/vue-leaflet"
    import "leaflet/dist/leaflet.css";
    import L from "leaflet";
    import {mapGetters} from 'vuex'

    delete L.Icon.Default.prototype._getIconUrl;
    L.Icon.Default.mergeOptions({
        iconRetinaUrl: new URL("leaflet/dist/images/marker-icon-2x.png",import.meta.url).href,
        iconUrl: new URL("leaflet/dist/images/marker-icon.png", import.meta.url).href,
        shadowUrl: new URL("leaflet/dist/images/marker-shadow.png", import.meta.url).href
    });

    export default{
        
        name: "MapView",
        props: {
            markers:Array,
            allowClick: {
                type: Boolean,
                default: true
            },
            user: Object
        },
        computed: {
            ...mapGetters('auth',['getUser','getToken'])
        },
        emits: ['mapClick', 'viewPost', 'delete','type-selected'],
        components: {
            LMap,
            LTileLayer,
            LMarker,
            LPopup
        },
        data(){
            return {
                center: [39.070946540300426, -0.266371446900382],
                tileUrl: "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
                tileAttribution: "OpenStreetMap contributors",
                types: [],
                selectedType: '',
                showFilters: false
            }
        },
        created(){
            fetch('http://localhost:8000/api/types',{
                headers: {
                    'Authorization':`Bearer ${this.getToken}`
                }
            })
                .then(response => response.json())
                .then(data => {
                    this.types = data;
                })
                .catch(error => {
                    console.error('Error al carregar els tipus:', error)
                })
        },
        methods: {
            handleMapClick(event){
                const { latlng } = event;
                this.$emit("mapClick", latlng)
            },
            isClickable(event){
                if(this.allowClick){
                    this.handleMapClick(event)
                }
            },
            emitType(){
                this.$emit('type-selected', this.selectedType)
                this.showFilters = false;
            },
            
        }
    }
</script>

<style scoped>
.slide-filters-enter-active, .slide-filters-leave-active {
  transition: max-height 0.3s ease, padding 0.3s ease;
}
.slide-filters-enter-from, .slide-filters-leave-to {
  max-height: 0;
  padding-top: 0;
  padding-bottom: 0;
  overflow: hidden;
}
.slide-filters-enter-to, .slide-filters-leave-from {
  max-height: 100px; 
  padding-top: 0.75rem;  
  padding-bottom: 0.75rem;
  overflow: hidden;
}
</style>
