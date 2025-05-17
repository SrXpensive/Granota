<template>
  <div class="relative">
    <MapView :markers="markers" @mapClick="onMapClick" />
    <MarkerForm :visible="showForm" :latlng="clickedLatLng" @submit="saveMarker" @close="showForm = false"/>
  </div>
</template>

<script>
import MapView from '@/components/MapView.vue'
import MarkerForm from '@/components/MarkerForm.vue'

export default {
  name: 'MapAndForm',
  components: {MapView, MarkerForm},
  data(){
    return {
      markers: [],
      showForm: false,
      clickedLatLng: null
    }
  },
  methods: {
    async fetchMarkers(){
      const token = localStorage.getItem('token');
      try {
        const response = await fetch('http://localhost:8000/api/markers', {
          method: 'GET',
          headers: {
            'Content-type':'application/json',
            'Authorization': `Bearer ${token}`
          }
        });
        if(!response.ok){
          throw new Error('Error al carregar els marcadors');
        }
        const data = await response.json();
        this.markers = data;
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
      const token = localStorage.getItem('token');

      const formData = new FormData();
      formData.append('title', markerData.title);
      formData.append('description', markerData.description);
      formData.append('category', markerData.category);
      formData.append('lat', markerData.lat);
      formData.append('lng',markerData.lng);
      formData.append('image', markerData.image);

      for(let pair of formData.entries()){
        console.log(pair[0]+'-'+pair[1])
      }
      
      try {
        const response = await fetch('http://localhost:8000/api/markers',{
          method: 'POST',
          headers: {
            'Authorization':`Bearer ${token}`
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
