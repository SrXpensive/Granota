<template>
    <div class="min-h-screen bg-gray-50 flex items-center justify-center p-6">
        <div class="bg-white p-8 rounded-2xl w-full max-w-md animate-fade-in">
            <h2 class="text-3xl font-bold text-green-700 mb-6 text-center">Perfil d'Usuari</h2>

            <div class="space-y-4">
                <div>
                    <label class="text-gray-600 font-semibold">Nom d'usuari: </label>
                    <p class="text-lg text-gray-800">{{ user.nickname }}</p>
                </div>
                <div>
                    <label class="text-gray-600 font-semibold">Correu electrònic: </label>
                    <p class="text-lg text-gray-800">{{ user.email }}</p>
                </div>
                <div v-if="readableRoles.length">
                    <label class="text-gray-600 font-semibold">Rols:</label>
                    <p class="text-lg text-gray-800">{{ readableRoles.join(', ') }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ProfileView',
    data(){
        return {
            user: {
                nickname: '',
                email: '',
                roles: []
            },
            rolenames: {
                ROLE_USER: 'Usuari',
                ROLE_ADMIN: 'Administrador',
                ROLE_REVISOR: 'Colaborador'
            }
        }
    },
    computed: {
        readableRoles(){
            return this.user.roles.map(role => this.rolenames[role] || role)
        }
    },
    async created(){
        try {
            const token = localStorage.getItem('token');
            const response = await fetch('http://localhost:8000/api/profile',{
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            });

            if(!response.ok){
                throw new Error("No s'han pogut carregar les dades de l'usuari.");
            }

            const data = await response.json();
            this.user = data;
        }catch(e){
            console.error(e.message)
        }
    }
}
</script>

<style scoped>
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(15px)
    }
    to {
        opacity: 1;
        transform: translateY(0)
    }
}

.animate-fade-in {
    animation: fadeIn 0.8s ease-out forwards;
}
</style>