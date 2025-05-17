<template>
    <div class="min-h-screen flex items-center justify-center bg-green-50 p-4">
        <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-md animate-fade-in">
            <h2 class="text-3xl font-bold text-green-700 mb-6 text-center">Registrar-se</h2>
            <form @submit.prevent="handleRegister" class="flex flex-col gap-4">
                <input v-model="nickname" type="text" placeholder="Nom d'usuari" class="border p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-300" required />
                <input v-model="email" type="email" placeholder="Correu electrònic" class="border p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-300" required />
                <input v-model="password" type="password" placeholder="Contrassenya" class="border p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-300" required />
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-xl transition shadow-md">
                    Registrar-se
                </button>
            </form>
            <p v-if="error" class="text-red-500 text-center mt-4">{{ error }}</p>
        </div>
    </div>
</template>

<script>
export default {
    name: "RegisterView",
    data(){
        return {
            nickname: "",
            email: "",
            password: "",
            error: null
        }
    },
    methods: {
        async handleRegister(){
            this.error = null;
            try{
                const response = await fetch('http://localhost:8000/api/register', {
                    method: 'POST',
                    headers : {'Content-type': 'application/json'},
                    body: JSON.stringify({
                        nickname: this.nickname,
                        email: this.email,
                        password: this.password
                    })
                });
                const data = await response.json();
                if(!response.ok){
                    throw new Error(data.error)
                }

                this.$router.push('/login');
            } catch(err){
                this.error = err.message;
            }

        }
    }
}
</script>

<style scoped>
@keyframes fadeIn {
    from {
        opacity: 0; 
        transform: translateY(20px);
    }
    to{
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fadeIn 1s ease-out forwards;
}
</style>