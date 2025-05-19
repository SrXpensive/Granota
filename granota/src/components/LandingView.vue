<template>
    <div class="min-h-screen bg-gradient-to-b from-green-200 via-green-100 to-white flex flex-col items-center justify-center text-center p-4">
        <img src="@/assets/frog_icon.svg" alt="Logo" class="w-60 h-60 mb-6 animate-fade-in">
        <h1 class="text-5xl md:text-7xl font-bold text-green-800 mb-6 animate-fade-in">
            
            Granota
        </h1>
        <p class="text-lg md:text-2xl text-green-700 mb-8 animate-fade-in-delay opacity-0">
            <span class="font-bold">G</span>estió de 
            <span class="font-bold">R</span>ecursos 
            <span class="font-bold">A</span>mbientals i 
            <span class="font-bold">N</span>aturals per a l'
            <span class="font-bold">O</span>bservació del 
            <span class="font-bold">T</span>erritori i l'
            <span class="font-bold">A</span>igua
        </p>
        <div class="flex flex-col md:flex-row gap-4">
            <router-link to="/register" class="px-6 py-3 bg-green-600 text-white rounded-xl text-lg font-semibold hover:bg-green-700 transition shadow-lg active:scale-95 hover:scale-105">
                Regístra't
            </router-link>
            <router-link to="/login" class="px-6 py-3 bg-white text-green-600 border border-green-600 rounded-xl text-lg font-semibold hover:bg-green-100 transition shadow-lg active:scale-95 hover:scale-105">
                Inicia sessió
            </router-link>
            <router-link to="/guest" class="px-6 py-3 bg-yellow-100 text-green-800 border border-yellow-300 rounded-xl text-lg font-semibold hover:bg-yellow-200 transition shadow-lg active:scale-95 hover:scale-105">
                Consulta els marcadors!
            </router-link>
        </div>
    </div>
</template>

<script>
export default{
    name: "LandingView"
}
</script>

<style scoped>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fadeIn 1s ease-out forwards;
    }

    .animate-fade-in-delay {
        animation: fadeIn 1s ease-out forwards;
        animation-delay: 0.5s;
    }
</style>