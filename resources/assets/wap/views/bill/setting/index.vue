<template>
    <n-card>
        <n-tabs :default-value="tab_value" justify-content="space-evenly" type="line" @update:value="(value) => (tab_value = value)">
            <n-tab name="payee" tab="收款者"></n-tab>
            <n-tab name="currency" tab="货币"></n-tab>
            <n-tab name="subject" tab="科目"></n-tab>
        </n-tabs>
    </n-card>
</template>
<script lang="ts" setup>
    import fly from '@wap/utils/fly'
    import { useRouter, useRoute } from 'vue-router'
    import { useStore } from 'vuex'
    import { ref } from 'vue'

    const router = useRouter(),
        route = useRoute(),
        store = useStore()

    const tab_value = ref('payee')
</script>
<style lang="stylus"></style>
