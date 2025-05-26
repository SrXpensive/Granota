<template>
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 h-[500px] flex flex-col">
    <h2 class="text-2xl font-bold mb-4">Últims comentaris</h2>
    <div v-if="loading">Cargando comentarios...</div>
    <div v-else-if="error" class="error">{{ error }}</div>
    <div v-else class="overflow-y-auto pr-2 space-y-4 flex-1">
      <CommentCard
        v-for="note in notes"
        :key="note.id"
        :comment="note"
        :marker="note.marker"
      />
    </div>
  </div>
</template>

<script>
import CommentCard from '@/components/CommentCard.vue'
import {mapGetters} from 'vuex'
export default {
  name: 'CommentList',
  components: {
    CommentCard
  },
  data() {
    return {
      notes: [],
      loading: false,
      error: null,
    }
  },
  computed:{
    ...mapGetters('auth',['getToken'])
  },
  methods: {
    fetchNotes() {
      this.loading = true
      this.error = null
      fetch('http://localhost:8000/api/notes/all',{
        headers:{
            'Authorization':`Bearer ${this.getToken}`
        }
      })
        .then(res => {
          if (!res.ok) throw new Error('Error al cargar los comentarios')
          return res.json()
        })
        .then(data => {
          this.notes = data
        })
        .catch(err => {
          this.error = err.message
        })
        .finally(() => {
          this.loading = false
        })
    }
  },
  created() {
    this.fetchNotes()
  }
}
</script>

<style scoped>
.comment-card {
  border: 1px solid #ccc;
  padding: 10px;
  margin-bottom: 10px;
  border-radius: 6px;
}
.error {
  color: red;
}
</style>
