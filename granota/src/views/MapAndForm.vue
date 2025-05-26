<template>
  <div class="relative pt-20">
    <MapView :markers="markers" :allowClick="true" @mapClick="onMapClick" @viewPost="openPost" @delete="handleDeleteMarker" :user="getUser" @type-selected="fetchMarkers"/>
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
      selectedPost: null,
      showPostView: false
    }
  },
  computed: {
    ...mapGetters('auth', ['getToken', 'isAuthenticated', 'getUser'])
  },
  methods: {
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
            'Authorization': `Bearer ${this.getToken}`
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
          console.log(this.markers)
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
      console.log(markerData.speciesId)
      formData.append('speciesId', markerData.speciesId);

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
    },
    async handleDeleteMarker(marker){
      if(!confirm('Estàs segur de voler eliminar aquest marcador?')){
        return;
      }
      try{
        const response = await fetch(`http://localhost:8000/api/markers/${marker.id}`,{
          method: 'DELETE',
          headers: {
            'Authorization':`Bearer ${this.getToken}`
          }
        })
        if(!response.ok) throw new Error('Error al eliminar marcador');

        this.markers = this.markers.filter(m => m.id !== marker.id)
      }catch(error){
        console.error(error.message);
      }
    }
    
  },
  mounted(){
    setTimeout(()=>{
      this.fetchMarkers()
    },0);
  }
}
</script>
