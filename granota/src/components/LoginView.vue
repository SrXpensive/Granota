<template>
    <div class="min-h-screen flex items-center justify-center bg-green-50 p-4">
        <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-md animate-fade-in">
            <h2 class="text-3xl font-bold text-green-700 mb-6 text-center">Iniciar sessió</h2>
            <form @submit.prevent="handleLogin" class="flex flex-col gap-4">
                <input v-model="email" type="email" placeholder="Correu electrònic" class="border p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-300" required />
                <input v-model="password" type="password" placeholder="Contrassenya" class="border p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-300" required />
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-xl transition shadow-md">
                    Entrar
                </button>
            </form>
            <p v-if="error" class="text-red-500 text-center mt-4">{{ error }}</p>
        </div>
    </div>
</template>

<script>
import {mapActions} from 'vuex'
export default {
    name: "LoginView",
    data(){
        return{
            email:"",
            password:"",
            error: null   
        }
    },
    methods:{
        ...mapActions('auth',['login']),
        async handleLogin(){
            this.error = null;
            try{
                await this.login({email: this.email, password: this.password})
                this.$router.push('/home/dashboard')
            }catch(err){
                this.error = err.message;
            }
        }
    }
}
</script>

<style scoped>
@keyframes fadeIn {
    from{
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0)
    }
}

.animate-fade-in {
    animation: fadeIn 1s ease-out forwards;
}
</style>