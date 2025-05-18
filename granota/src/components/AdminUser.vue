<template>
    <div class="p-4">
        <h2 class="text-xl font-bold mb-4">Administració d'Usuaris</h2>

        <div v-if="loading">Cargando usuarios...</div>
        <div v-else>
            <table class="min-w-full border">
                <thead>
                    <tr>
                        <th class="border p-2">ID</th>
                        <th class="border p-2">Nickname</th>
                        <th class="border p-2">Roles</th>
                        <th class="border p-2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users" :key="user.id">
                        <td class="border p-2">{{ user.id }}</td>
                        <td class="border p-2">{{ user.nickname }}</td>
                        <td class="border p-2">
                            <select v-model="user.roles" class="border rounded p-1">
                                <option value="ROLE_USER">Usuari</option>
                                <option value="ROLE_ADMIN">Admin</option>
                                <option value="ROLE_REVISOR">Colaborador</option>
                            </select>
                        </td>
                        <td class="border p-2 space-x-2">
                            <button @click="updateUser(user)" class="bg-blue-500 text-white px-2 py-1 rounded">Guardar</button>
                            <button @click="deleteUser(user.id)" class="bg-red-500 text-white px-2 py-1 rounded">Eliminar</button>
                        </td>
                    </tr>  
                </tbody>
            </table>
        </div>
        <div v-if="error" class="mt-4 text-red-600">{{ error }}</div>
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
                const res = await fetch('http://localhost:8000/api/admin/users', {
                    headers: {
                        'Authorization': `Bearer ${this.getToken}`
                    }
                })

                if(!res.ok) throw new Error('Error al carregar usuaris')
                const data = await res.json();
                this.users = data.map(user =>({
                    ...user,
                    roles: user.roles[0] || 'ROLE_USER'
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
                        roles: user.roles
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