<template>
    <n-card>
        <n-page-header :title="props.title" @back="router.back()"></n-page-header>
    </n-card>
</template>
<script lang="ts" setup>
    import { useRouter } from 'vue-router'

    const router = useRouter()
    const props = defineProps({
        title: String,
    })
</script>
<style lang="stylus"></style>
