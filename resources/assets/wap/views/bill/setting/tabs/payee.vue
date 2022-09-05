<template>
    <n-dynamic-input v-model:value="payee" preset="pair" key-placeholder="收款者" value-placeholder="备注" show-sort-button />
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

    const payee = ref([]),
        formSubmit = () => {
            fly.post('/bill/{bill_id}/setting/payee', {
                payee: payee.value.map((row) => {
                    return {
                        name: row.key,
                        remark: row.value,
                    }
                }),
            }).then((res) => {
                store.state.naive.message.success('保存成功')
            })
        }

    fly.get('/bill/{bill_id}/setting/payee').then((res: any) => {
        payee.value = res.map((row) => {
            return {
                key: row.name,
                value: row.remark,
            }
        })
    })
</script>
<style lang="stylus"></style>
