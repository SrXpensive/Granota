<template>
    <nav class="flex items-center justify-between px-6 py-4 bg-gradient-to-r from-green-100 via-green-50 to-green-100 border-b-4 border-green-600 shadow-md rounded-b-xl">
        <div class="flex gap-8 text-green-900 font-semibold text-lg">
            <router-link to="/home/dashboard" class="relative group">
                Dashboard
                <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-green-600 transition-all group-hover:w-full"></span>
            </router-link>
            <router-link to="/home/perfil" class="relative group">
                Perfil
                <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-green-600 transition-all group-hover:w-full">
                </span> 
            </router-link>
            <router-link to="/home/map" class="relative group">
                Mapa 
                <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-green-600 transition-all group-hover:w-full">
                </span>
            </router-link>
            <router-link v-if="isAdmin" to="/home/admin" class="relative group">
                Panel d'Administrador
                <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-green-600 transition-all group-hover:w-full">
                </span>
            </router-link>
        </div>
        <button @click="handleLogout" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-md shadow transition">Tancar sessió</button>
    </nav>
</template>

<script>
import {mapGetters, mapActions} from 'vuex'
export default {
    computed:{
        ...mapGetters('auth',['isAdmin'])
    },
    methods: {
        ...mapActions('auth',['logout']),
        async handleLogout(){
            await this.logout()
            this.$router.push("/")
        }
    }
}
</script>

<style scoped>
</style>