<template>
    <div class="create">
        <div class="button">
            <n-icon size="50" :component="AddCircleOutline" @click="toCreateOrUpdate()" />
        </div>
    </div>
</template>
<script lang="ts" setup>
    import { useRouter, useRoute } from 'vue-router'
    import { AddCircleOutline } from '@vicons/ionicons5'

    const router = useRouter(),
        route = useRoute()

    const toCreateOrUpdate = (id = null) => {
        router.push({
            name: 'BillCreateOrUpdate',
            query: {
                id,
            },
        })
    }
</script>
<style lang="stylus">
    .create
        .button
            position: absolute
            bottom: 50px
            right: 50px
</style>
