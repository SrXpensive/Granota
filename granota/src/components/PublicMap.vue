    <template>
        <div class="fixed top-0 left-0 right-0 z-50 flex flex-row justify-between px-6 py-5 bg-gradient-to-r from-green-100 via-green-50 to-green-100 border-b-4 border-green-600 shadow-md">
            
            <h2 class="text-2xl font-bold text-green-800 text-center">Explora els marcadors</h2>
            <router-link to="/">
                <button class="bg-red-400 hover:bg-red-500 text-white font-semibold rounded-xl py-2 px-4 shadow-xl border border-red-600 transition">Tornar</button>
            </router-link>
        </div>
        <div class="pt-20">
            <MapView :markers="markers" :allowClick="isAuthenticated" @viewPost="viewPost" @type-selected="fetchMarkers"/>
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
                setTimeout(()=>{
                    this.fetchMarkers()
                },0)
            },
            methods: {
                viewPost(marker){
                    this.selectedMarker = marker
                    this.showPost = true
                },
                async fetchMarkers(speciesType = null){
                    try {
                        console.log(speciesType)
                        const url = speciesType 
                        ? `http://localhost:8000/api/markers/by-type/${encodeURIComponent(speciesType)}`
                        : `http://localhost:8000/api/markers`;
                        const response = await fetch(url, {
                            method: 'GET',
                            headers: {
                                'Content-type':'application/json',
                            }
                        });

                        if(!response.ok){
                            throw new Error('Error al carregar els marcadors');
                        }

                        const data = await response.json();
                        console.log(data)
                        this.markers = data.map(m => ({
                            ...m,
                            lat: parseFloat(m.lat),
                            lng: parseFloat(m.lng)
                        }))
                            .filter(m=>
                                !isNaN(m.lat) &&
                                !isNaN(m.lng)
                            )
                    }catch(e){
                        console.error(e.message)
                    }
                }
            }
        }
    </script>

    <style scoped>
    </style>