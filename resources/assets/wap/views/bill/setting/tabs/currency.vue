<template>
    <n-dynamic-input v-model:value="currency" show-sort-button placeholder="货币 如：人民币、积分等" :min="1" />
    <br />
    <n-button type="info" block @click="formSubmit">保存</n-button>
</template>
<script lang="ts" setup>
    import fly from '@wap/utils/fly'
    import { useRouter, useRoute } from 'vue-router'
    import { useStore } from 'vuex'
    import { ref } from 'vue'

    const router = useRouter(),
        route = useRoute(),
        store = useStore()

    const currency = ref([]),
        formSubmit = () => {
            fly.post('/bill/{bill_id}/setting/currency', {
                currency: currency.value,
            }).then((res) => {
                store.state.naive.message.success('保存成功')
            })
        }

    fly.get('/bill/{bill_id}/setting/currency').then((res: any) => {
        currency.value = res.map((row) => row.name)
    })
</script>
<style lang="stylus"></style>
