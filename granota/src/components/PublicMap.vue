<template>
    <div class="relative px-6 py-4 bg-gradient-to-r from-green-100 via-green-50 to-green-100 border-b-4 border-green-600 shadow-md rounded-b-xl">
        <router-link to="/" class="absolute left-6 top-1/2 -translate-y-1/2">
            <button class="bg-red-400 hover:bg-red-500 text-white font-semibold rounded-xl py-2 px-4 shadow-xl border border-red-600 transition">Tornar</button>
        </router-link>
        <h2 class="text-2xl font-bold text-green-800 text-center">Explora els marcadors</h2>
    </div>
    
    <MapView :markers="markers" :allowClick="isAuthenticated"/>
</template>

<script>
    import MapView from '@/components/MapView.vue'
    import { mapGetters } from 'vuex'
    export default{
        components:{
            MapView
        },
        data(){
            return {
                markers: [],
            }
        },
        computed: {
            ...mapGetters('auth', ['isAuthenticated'])
        },
        async mounted(){
            try{
                const res = await fetch(`http://localhost:8000/api/markers`)
                this.markers = await res.json()
            }catch(e){
                console.error("Error al carregar els marcadors", e)
            }
        }
    }
</script>

<style scoped>
</style>