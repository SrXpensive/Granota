<template>
    <nav class="flex gap-4 p-4 bg-green-100 shadow">
        <h3><router-link to="/home/dashboard">Dashboard</router-link></h3>
        <router-link to="/home/perfil">Perfil </router-link>
        <router-link to="/home/map">Mapa </router-link>
        <router-link v-if="isAdmin" to="/home/admin">Panel d'Administrador</router-link>
        <button @click="handleLogout">Tancar sessió</button>
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