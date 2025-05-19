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
                v-for="(marker,index) in markers"
                :key="index"
                :lat-lng="[marker.lat, marker.lng]"
            >
                <l-popup>
                    <img
                        v-if="marker.image"
                        :src="`http://localhost:8000/uploads/${marker.image}`"
                        alt="Imagen del marcador"
                        class="mt-2 max-h-32 object-cover rounded"
                    />
                    <strong>{{ marker.title }}</strong><br/>
                    {{ marker.description }}
                    <div class="mt-2 text-right">
                        <button @click="$emit('viewPost', marker)" class="text-sm text-blue-600 underline">Veure més</button>
                    </div>
                </l-popup>
            </l-marker>
    </l-map>
</template>

<script>
    import {LMap, LTileLayer, LMarker, LPopup} from "@vue-leaflet/vue-leaflet"
    import "leaflet/dist/leaflet.css";
    import L from "leaflet";

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
            }
        },
        emits: ['mapClick'],
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
