<template>
    <div class="pt-24 px-6 flex justify-center">
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
</style>