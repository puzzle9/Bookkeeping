<template>
    <n-layout>
        <n-spin v-if="props.loading" class="loading"></n-spin>
        <n-card v-else>
            <slot name="default"></slot>
        </n-card>
    </n-layout>
</template>
<script lang="ts" setup>
    const props = defineProps({
        loading: Boolean,
    })
</script>
<style lang="stylus">
    .loading
        margin 20px
        display flex
        justify-content: center
</style>
