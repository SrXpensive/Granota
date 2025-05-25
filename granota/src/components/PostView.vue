<template>
    <transition name="slide">
        <div v-if="visible" class="panel">
            <div class="p-4 overflow-y-auto h-full flex flex-col">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">{{ marker.title }}</h2>
                    <button @click="$emit('close')" class="text-red-600 hover:text-red-800">&times;</button>
                </div>

                <img v-if="marker.image" :src="`http://localhost:8000/uploads/${marker.image}`" class="rounded mb-4 max-h-64 object-cover w-full">

                <p class="mb-4 text-gray-700">{{ marker.description }}</p>

                <div class="mt-auto">
                    <h3 class="text-lg font-semibold mt-6 mb-2">Comentaris</h3>
                    <div v-if="comments.length === 0" class="text-gray-500">Encara no hi ha comentaris.</div>
                    <ul class="space-y-2">
                        <li v-for="(comment,index) in comments" :key="index" class="border-b pb-2">
                            <strong>{{ comment.user.nickname }}</strong>: {{ comment.note }}
                        </li>
                    </ul>

                    <div class="mt-4">
                        <div v-if="isAuthenticated && canComment">
                            <textarea v-model="newComment" class="w-full border rounded p-2" placeholder="Escriu un comentari..."></textarea>
                            <button @click="submitComment" class="mt-2 px-4 py-1 bg-green-600 text-white rounded hover:bg-green-700">Enviar</button>
                        </div>
                        <div v-else-if="!isAuthenticated" class="text-gray-600 italic">
                            Inicia sessió per comentar.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
    import {mapGetters} from 'vuex'
    export default{
        props: {
            visible: Boolean,
            marker: Object,
            user: Object,
        },
        data(){
            return {
                comments: [],
                newComment: ''
            }
        },
        computed:{
            ...mapGetters('auth',{
                isAuthenticated:'isAuthenticated',
                token: 'getToken'}),
            canComment(){
                return this.user && (this.user.roles.includes('ROLE_REVISOR') || this.user.roles.includes('ROLE_ADMIN'))
            }
        },
        watch:{
            marker:{
                immediate: true,
                handler(newMarker){
                    if (newMarker && newMarker.id){
                        this.fetchComments(newMarker.id)
                    }
                }
            }
        },
        methods: {
            async fetchComments(markerId){
                try {
                    const res = await fetch(`http://localhost:8000/api/markers/${markerId}/notes`,{
                        headers: {
                            'Content-type':'application/json',
                            ...(this.token ? {'Authorization': `Bearer ${this.token}`}:{})
                        }
                    })
                    if(!res.ok) throw new Error("No s'han pogut carregar els comentaris")

                    this.comments = await res.json();
                }catch(e){
                    console.error("Error carregant els comentaris", e)
                }
            },
            async submitComment(){
                if(!this.newComment.trim()) return;

                try{
                    const res = await fetch(`http://localhost:8000/api/markers/${this.marker.id}/notes`,{
                        method: 'POST',
                        headers: {
                            'Content-type':'application/json',
                            'Authorization': `Bearer ${this.token}`
                        },
                        body: JSON.stringify({
                            marker: this.marker.id,
                            note: this.newComment
                        })
                    })

                    if(!res.ok) throw new Error("Error al enviar comentari")

                    const newCommentData = await res.json();
                    this.comments.push(newCommentData);
                    this.newComment = ''
                }catch(e){
                    console.error(e.message)
                }
            }
        }
    }
</script>

<style scoped>
    .panel {
        position: fixed;
        top: 0;
        right: 0;
        width: 100%;
        height: 100%;
        max-width: 400px;
        background: white;
        box-shadow: -2px 0 8px rgba(0,0,0,0.2);
        z-index: 50;
    }

    .slide-enter-active, 
    .slide-leave-active {
        transition: transform 0.3s ease;
    }
    .slide-enter-from,
    .slide-leave-to {
        transform: translateX(100%);
    }
</style>