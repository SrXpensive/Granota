<template>
    <div class="min-h-screen bg-gray-50 flex items-center justify-center p-6 pt-20">
        <div class="bg-white p-8 rounded-2xl w-full max-w-md animate-fade-in">
            <h2 class="text-3xl font-bold text-green-700 mb-6 text-center">Perfil d'Usuari</h2>

            <div class="space-y-4">
                <div class="flex flex-col items-center mb-4">
                    <label class="text-gray-600 font-semibold">Avatar </label>
                    <img :src="user.avatar ? `http://localhost:8000/${user.avatar}` : '/img/default-avatar.jpg'" alt="Avatar" class="w-32 h-32 rounded-full object-cover border"/>
                    <p class="text-gray-600 font-semibold">Editar avatar:</p>
                    <input type="file" @change="uploadAvatar" class="mt-2"/>
                </div>
                <div>
                    <label class="text-gray-600 font-semibold">Nom d'usuari: </label>
                    <p class="text-lg text-gray-800">{{ user.nickname }}</p>
                </div>
                <div>
                    <label class="text-gray-600 font-semibold">Correu electrònic: </label>
                    <p class="text-lg text-gray-800">{{ user.email }}</p>
                </div>
                <div v-if="readableRoles.length">
                    <label class="text-gray-600 font-semibold">Rol:</label>
                    <p class="text-lg text-gray-800">{{ readableRoles[0] }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {useToast} from 'vue-toastification'
export default {
    name: 'ProfileView',
    data(){
        return {
            user: {
                nickname: '',
                email: '',
                roles: [],
                avatar: null
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
            if(!this.user.roles.length) return [];
            const firstRole = this.user.roles[0];
            return [this.rolenames[firstRole]]
        }
    },
    async created(){
        try {
            const token = localStorage.getItem('token');
            const response = await fetch(`http://localhost:8000/api/profile`,{
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
    },
    methods:{
        async uploadAvatar(event) {
            const toast = useToast();
            const file = event.target.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('avatar', file);

            const token = localStorage.getItem('token');
            try{
                const response = await fetch(`http://localhost:8000/api/profile/avatar`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    },
                    body: formData
                });
                if(!response.ok){
                    const errorData = await response.json();
                    throw new Error(errorData.error || 'Error desconegut al pujar el avatar')
                }
                const data = await response.json();
                this.user.avatar = data.avatar;
                toast.success('Avatar pujat amb èxit!')
            }catch(error){
                toast.error(error.message)
            }
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