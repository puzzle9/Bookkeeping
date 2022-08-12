<template>
    <n-card>
        <n-page-header :title="props.title" @back="router.back()">
            <template #back v-if="hiddenBack"></template>
        </n-page-header>
    </n-card>
</template>
<script lang="ts" setup>
    import { useRouter } from 'vue-router'

    const router = useRouter()
    const props = defineProps({
        title: String,
        hiddenBack: {
            type: Boolean,
            default: false,
        },
    })
</script>
<style lang="stylus"></style>
