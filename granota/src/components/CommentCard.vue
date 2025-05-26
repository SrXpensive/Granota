<template>
  <div class="flex space-x-4 p-2 bg-white rounded-lg shadow-sd border border-gray-200 mb-4 hover:shadow-lg transition-shadow duration-200">
    <img
      :src="comment.user.avatar ? `http://localhost:8000${comment.user.avatar}` : '/img/default-avatar.jpg'"
      class="w-12 h-12 rounded-full object-cover"
      alt="avatar"
    />
    <div class="flex-1">
      <div class="flex justify-between items-center">
        <h4 class="font-semibold">{{ comment.user.nickname }}</h4>
        <span class="text-xs text-gray-500">{{ formatDate(comment.createdAt) }}</span>
      </div>
      <p class="text-gray-700">{{ comment.note }}</p>
      <div v-if="marker" class="text-sm italic text-blue-600">
        Publicación: {{ marker.title }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    comment: Object,
    marker: {
        type: Object,
        default: null
    }
  },
  methods: {
    formatDate(dateStr) {
      const date = new Date(dateStr);
      return date.toLocaleString();
    }
  }
}
</script>

<style scoped>
</style>
