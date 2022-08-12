<template>
    <NavBar title="账本列表" hiddenBack></NavBar>
    <Layout :loading="loading">
        <n-card v-for="data in datas" class="list" closable @close="accountDelete(data)" @click="toInfo(data.id)">
            <template #header>
                {{ data.name }}
            </template>
            <template #header-extra>
                {{ dayjs(data.updated_at).fromNow() }}
            </template>
            {{ data.remark }}
        </n-card>
        <n-button block type="info" class="create" @click="toCreateOrUpdate()">新建账本</n-button>
    </Layout>
</template>
<script lang="ts" setup>
    import dayjs from '@wap/utils/dayjs'
    import fly from '@wap/utils/fly'
    import { Ref, ref } from 'vue'
    import { useRouter, useRoute } from 'vue-router'
    import { useStore } from 'vuex'

    const router = useRouter(),
        route = useRoute(),
        store = useStore()

    const loading = ref(true),
        datas: any = ref([])

    fly.get('/book/account').then((res) => {
        datas.value = res
        loading.value = false
    })

    const toCreateOrUpdate = (id = null) => {
        router.push({
            name: 'BookAccountCreateOrUpdate',
            query: {
                id,
            },
        })
    }

    const toInfo = (bill_id) => {
        router.push({
            name: 'BillLists',
            params: {
                bill_id,
            },
        })
    }

    const accountDelete = (data) => {
        store.state.naive.dialog.warning({
            title: `确认删除 ${data.name} 账本?`,
            positiveText: '确定',
            negativeText: '取消',
            onPositiveClick: () => {
                let data_id = data.id
                datas.value = datas.value.filter((row) => row.id != data_id)
                fly.delete('/book/account', {
                    id: data_id,
                }).then((res) => {
                    store.state.naive.message.success('删除成功')
                })
            },
        })
    }
</script>
<style lang="stylus">
    .list:not(:first-child)
        margin-top 10px

    .create
        margin-top 10px
</style>
