<template>
    <div class="min-h-screen bg-gradient-to-b from-green-100 via-green-50 to-white p-6">
        <h2 class="text-3xl font-bold text-green-800 mb-6 text-center pt-20">Administració d'Usuaris</h2>

        <div v-if="loading" class="text-green-700 text-center">Carregant usuaris...</div>
        <div v-else>
            <table class="min-w-full bg-white shadow-md rounded-xl overflow-hidden">
                <thead class="bg-green-200 text-green-900">
                    <tr>
                        <th class="border p-2">ID</th>
                        <th class="border p-2">Nickname</th>
                        <th class="border p-2">Rol</th>
                        <th class="border p-2">Accions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users" :key="user.id" class="border-b hover:bg-green-50 transition">
                        <td class="border p-2">{{ user.id }}</td>
                        <td class="border p-2">{{ user.nickname }}</td>
                        <td class="border p-2">
                            <select v-model="user.roles[0]" class="border border-green-300 rounded-lg px-2 py-1 bg-white text-green-800">
                                <option value="ROLE_USER">Usuari</option>
                                <option value="ROLE_ADMIN">Admin</option>
                                <option value="ROLE_REVISOR">Colaborador</option>
                            </select>
                        </td>
                        <td class="px-4 py-2 space-x-2">
                            <button @click="updateUser(user)" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-lg shadow transition">Guardar</button>
                            <button @click="deleteUser(user.id)" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg shadow transition">Eliminar</button>
                        </td>
                    </tr>  
                </tbody>
            </table>
        </div>
        <div v-if="error" class="mt-6 text-center text-red-600 font-semibold">{{ error }}</div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default{
    name: 'AdminUser',
    data(){
        return{
            users: [],
            loading: false,
            error: null
        }
    },
    computed: {
        ...mapGetters('auth',['getToken', 'isAuthenticated', 'isAdmin'])
    },
    methods: {
        async fetchUsers(){
            this.loading = true
            this.error = null
            try{
                const res = await fetch(`http://localhost:8000/api/admin/users`, {
                    headers: {
                        'Authorization': `Bearer ${this.getToken}`
                    }
                })

                if(!res.ok) throw new Error('Error al carregar usuaris')
                const data = await res.json();
                this.users = data.map(user =>({
                    ...user,
                    roles: user.roles.length ? [...user.roles] : ["ROLE_USER"]
                }))
            }catch(e){
                this.error = e.message
            }finally{
                this.loading = false
            }
        },
        async updateUser(user){
            try{
                const res = await fetch(`http://localhost:8000/api/admin/users/${user.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type':'application/json',
                        'Authorization': `Bearer ${this.getToken}`
                    },
                    body: JSON.stringify({
                        roles: [user.roles[0]]
                    })
                })
            
                if(!res.ok) throw new Error("Error al actualitzar l'usuari")
                alert('Usuari actualitzat correctament')
            }catch(e){
                alert(e.message)
            }
        },
        async deleteUser(id){
            if(!confirm('¿Segur que vols eliminar aquest usuari?')) return
            try{
                const res = await fetch(`http://localhost:8000/api/admin/users/${id}`,{
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${this.getToken}`
                    }
                })
                if(!res.ok) throw new Error("Error al eliminar l'usuari")
                this.users = this.users.filter(user => user.id !== id)
                alert('Usuari eliminat')
            }catch(e){
                alert(e.message)
            }
        }
    },
    mounted(){
        if(this.isAuthenticated && this.isAdmin){
            this.fetchUsers()
        }else{
            this.$router.push('/home')
        }
    }
}
</script>

<style scoped>
</style>