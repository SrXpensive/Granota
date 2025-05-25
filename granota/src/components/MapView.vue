<template>
    <l-map
        style="height: 100vh; width: 100vw; z-index: 0;" 
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
                <l-popup>
                    {{ console.log(marker.id) }}
                    <img
                        v-if="marker.image"
                        :src="`http://localhost:8000/uploads/${marker.image}`"
                        alt="Imagen del marcador"
                        class="mt-2 max-h-64 object-cover rounded"
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
            ...mapGetters('auth',['getUser'])
        },
        emits: ['mapClick', 'viewPost', 'delete'],
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
            }
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
            }
        }
    }
</script>

<style scoped>
</style>
