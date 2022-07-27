<template>
    <div class="create">
        <div class="button">
            <van-icon name="add-o" size="50" @click="toCreateOrUpdate()" />
        </div>
    </div>
</template>
<script lang="ts" setup>
    import { useRouter, useRoute } from 'vue-router'

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
