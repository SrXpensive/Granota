<template>
  <div class="relative pt-20">
    <MapView :markers="markers" :allowClick="true" @mapClick="onMapClick" @viewPost="openPost"/>
    <MarkerForm :visible="showForm" :latlng="clickedLatLng" @submit="saveMarker" @close="showForm = false"/>
    <PostView :visible="showPostView" :marker="selectedPost" :token="getToken" :user="getUser" @close="showPostView = false"/>
  </div>
</template>

<script>
import MapView from '@/components/MapView.vue'
import MarkerForm from '@/components/MarkerForm.vue'
import PostView from '@/components/PostView.vue'
import { mapGetters } from 'vuex';

export default {
  name: 'MapAndForm',
  components: {MapView, MarkerForm, PostView},
  data(){
    return {
      markers: [],
      showForm: false,
      clickedLatLng: null,
      selectedMarker: null,
      showPostView: false
    }
  },
  computed: {
    ...mapGetters('auth', ['getToken', 'isAuthenticated', 'getUser'])
  },
  methods: {
    async fetchMarkers(){
      try {
        const response = await fetch(`http://localhost:8000/api/markers`, {
          method: 'GET',
          headers: {
            'Content-type':'application/json',
            'Authorization': `Bearer ${this.getToken}`
          }
        });

        if(!response.ok){
          throw new Error('Error al carregar els marcadors');
        }

        this.markers = await response.json();

      }catch(e){
        console.error(e.message)
      }
    },
    onMapClick(latlng){
      console.log("Click en el mapa:", latlng)
      this.clickedLatLng = latlng
      this.showForm = true
    },
    async saveMarker(markerData){
      const formData = new FormData();
      formData.append('title', markerData.title);
      formData.append('description', markerData.description);
      formData.append('category', markerData.category);
      formData.append('lat', markerData.lat);
      formData.append('lng',markerData.lng);
      formData.append('image', markerData.image);
      formData.append('species', markerData.speciesId);

      for(let pair of formData.entries()){
        console.log(pair[0]+'-'+pair[1])
      }
      
      try {
        const response = await fetch(`http://localhost:8000/api/markers`,{
          method: 'POST',
          headers: {
            'Authorization':`Bearer ${this.getToken}`
          },
          body: formData,
          credentials: 'include'
        });

        if(!response.ok){
          throw new Error('Error al guardar el marcador')
        }

        const result = await response.json();
        console.log(`Marcador guardat amb ID: ${result.id}`)

        this.markers.push(result)

      }catch(e){
        console.log(e.message)
      }
    },
    openPost(marker){
      this.selectedPost = marker;
      this.showPostView = true;
    }
  },
  mounted(){
    setTimeout(()=>{
      this.fetchMarkers()
    },0)
    ;
  }
}
</script>
