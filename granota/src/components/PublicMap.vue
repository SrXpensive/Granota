<template>
    <div class="fixed top-0 left-0 right-0 z-50 flex flex-row justify-between px-6 py-5 bg-gradient-to-r from-green-100 via-green-50 to-green-100 border-b-4 border-green-600 shadow-md">
        
        <h2 class="text-2xl font-bold text-green-800 text-center">Explora els marcadors</h2>
        <router-link to="/">
            <button class="bg-red-400 hover:bg-red-500 text-white font-semibold rounded-xl py-2 px-4 shadow-xl border border-red-600 transition">Tornar</button>
        </router-link>
    </div>
    <div class="pt-20">
        <MapView :markers="markers" :allowClick="isAuthenticated" @viewPost="viewPost"/>
    </div>
    
    <PostView :visible="showPost" :marker="selectedMarker" @close="showPost = false"/>
</template>

<script>
    import MapView from '@/components/MapView.vue'
    import PostView from '@/components/PostView.vue'
    import { mapGetters } from 'vuex'
    export default{
        components:{
            MapView, PostView
        },
        data(){
            return {
                markers: [],
                showPost: false,
                selectedMarker: null
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
        },
        methods: {
            viewPost(marker){
                this.selectedMarker = marker
                this.showPost = true
            }
        }
    }
</script>

<style scoped>
</style>