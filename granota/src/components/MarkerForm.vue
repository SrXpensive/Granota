<template>
    <div v-if="visible" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-xl max-w-md space-y-4 shadow-lg animate-fade-in">
            <h2 class="text-xl font-bold">Afegir espècimen</h2>
            <input v-model="title" class="input" placeholder="Titol">
            <textarea v-model="description" class="input" placeholder="Observacions"></textarea>

            <select v-model="type" class="input" @change="fetchSpecies">
                <option disabled value="">Selecciona un tipus</option>
                <option v-for="t in tipos" :key="t" :value="t">{{ t }}</option>
            </select>

            <select v-if="type" v-model="speciesId" class="input">
                <option disabled value="">Selecciona una espècie</option>
                <option v-for="sp in species" :key="sp.id" :value="sp.id">
                {{ sp.commonName }}
                </option>
                <option value="new">Nova espècie...</option>
            </select>

            <div v-if="speciesId === 'new'" class="space-y-2">
                <input v-model="newSpecies.commonName" class="input" placeholder="Nom comú" />
                <input v-model="newSpecies.scientificName" class="input" placeholder="Nom científic" />
                <textarea v-model="newSpecies.observations" class="input" placeholder="Observacions"></textarea>
            </div>

            <input type="file" @change="onFileChange" />
            <div class="flex justify-end gap-2">
                <button @click="cancel" class="btn">Cancelar</button>
                <button @click="submit" class="btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</template>

<script>
    import { useToast } from 'vue-toastification'
    export default {
        props: ['visible', 'latlng'],
        emits: ['submit', 'close'],
        data(){
            return {
                title: '',
                description: '',
                image: null,
                type: '',
                speciesId: '',
                tipos: [],
                species:[],
                newSpecies: {
                    commonName: '',
                    scientificName: '',
                    observations: '',
                    type: '',
                    imagenes: []
                }
            }
        },
        async mounted(){
            try{
                const res = await fetch('http://localhost:8000/api/types',{
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`
                    }
                })
                if(!res.ok) throw new Error('Error carregant tipus');
                this.tipos = await res.json();
            }catch(error){
                console.log('Error carregant tipus: ', error)
            }
        },
        methods: {
            async submit(){
                const toast = useToast();
                if(!this.speciesId || this.speciesId === ''){
                    toast.error('Has de seleccionar una espècie abans de guardar.');
                    return;
                }
                let finalSpeciesId = this.speciesId;
                
                if(this.speciesId === 'new'){
                    try{
                        const res = await fetch('http://localhost:8000/api/species',{
                            method: 'POST',
                            headers: {
                                'Content-type':'application/json',
                                'Authorization':`Bearer ${localStorage.getItem('token')}`
                            },
                            body: JSON.stringify({
                                ...this.newSpecies,
                                type: this.type
                            })
                        })
                        if(!res.ok) throw new Error('Error al crear espècie');
                        const data = await res.json();
                        finalSpeciesId = data.id;
                        toast.success('Espècie creada correctament!');
                    }catch(error){
                        console.error(error);
                        return;
                    }
                }
                this.$emit('submit',{
                    title: this.title,
                    description: this.description,
                    lat: this.latlng.lat,
                    lng: this.latlng.lng,
                    type: this.type,
                    speciesId: finalSpeciesId,
                    image: this.image
                    })
                    this.$emit('close')
                    toast.success('Marcador guardat correctament!');
                this.resetForm()
            },
            resetForm(){
                this.title="";
                this.description="";
                this.image = null;
                this.type = '';
                this.speciesId = '';
                this.species = [];
                this.newSpecies = {
                    commonName: '',
                    scientificName: '',
                    observations: '',
                    type: '',
                    imagenes: []
                };
            },
            cancel(){
                this.$emit('close')
                this.resetForm()
            },
            onFileChange(event){
                this.image = event.target.files[0];
            },
            async fetchSpecies(){
                if(!this.type){
                    this.species = []
                    return;
                } 
                try {
                    const response = await fetch(`http://localhost:8000/api/species?type=${this.type}`,{
                        headers: {
                            'Authorization':`Bearer ${localStorage.getItem('token')}`
                        }
                    });
                    if(!response.ok) throw new Error('Error carregant espècies');
                    this.species = await response.json();
                }catch(error){
                    console.log('Error carregant especies:', error);
                }
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