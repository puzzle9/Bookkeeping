<template>index</template>
<script lang="ts" setup>
    import fly from '@wap/utils/fly'

    fly.get('/').then((res) => {
        console.log(res)
    })
</script>
<style lang="stylus"></style>
