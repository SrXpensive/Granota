<template>
    <div class="pt-24 px-6 flex justify-center animate-fade-in">
        <div class="w-1/3 bg-white p-4 rounded-lg shadow-md">
            <CommentList/>
        </div>
    </div>
</template>

<script>
    import CommentList from '@/components/CommentList.vue';
    export default{
        components:{
            CommentList
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