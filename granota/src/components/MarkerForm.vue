<template>
    <div v-if="visible" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-xl w-96 space-y-4 shadow-lg animate-fade-in">
            <h2 class="text-xl font-bold">Añadir marcador</h2>
            <input v-model="title" class="input" placeholder="Título">
            <textarea v-model="description" class="input" placeholder="Descripcion"></textarea>
            <input type="file" @change="onFileChange"/>
            <div class="flex justify-end gap-2">
                <button @click="cancel" class="btn">Cancelar</button>
                <button @click="submit" class="btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['visible', 'latlng'],
        emits: ['submit', 'close'],
        data(){
            return {
                title: '',
                description: '',
                image: null
            }
        },
        methods: {
            submit(){
                this.$emit('submit',{
                    title: this.title,
                    description: this.description,
                    lat: this.latlng.lat,
                    lng: this.latlng.lng,
                    category: 'test',
                    image: this.image
                    })
                    this.$emit('close')
                this.resetForm()
            },
            resetForm(){
                this.title=""
                this.description=""
                this.image = null
            },
            cancel(){
                this.$emit('close')
                this.resetForm()
            },
            onFileChange(event){
                this.image = event.target.files[0];
            }
        }
    }
</script>

<style scoped>
    .input{
        @apply w-full border border-gray-300 rounded px-3 py-2;
    }

    .btn {
        @apply px-4 py-2 bg-gray-200 rounded;
    }

    .btn-primary {
        @apply px-4 py-2 bg-blue-600 text-white rounded;
    }
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
</style>