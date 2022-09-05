<template>
    <n-card>
        <n-tabs :default-value="tab_value" justify-content="space-evenly" type="line" @update:value="(value) => (tab_value = value)">
            <n-tab-pane name="payee" tab="收款者" display-directive="show:lazy">
                <SettingTabPayee />
            </n-tab-pane>
            <n-tab-pane name="currency" tab="货币" display-directive="show:lazy">
                <SettingTabCurrency />
            </n-tab-pane>
            <n-tab-pane name="type" tab="类别" display-directive="show:if">
                <SettingTabSubject subject_name="type" subject_title="类别" />
            </n-tab-pane>
            <n-tab-pane name="account" tab="账户" display-directive="show:if">
                <SettingTabSubject subject_name="account" subject_title="账户" />
            </n-tab-pane>
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

    import SettingTabPayee from '@wap/views/bill/setting/tabs/payee.vue'
    import SettingTabCurrency from '@wap/views/bill/setting/tabs/currency.vue'
    import SettingTabSubject from '@wap/views/bill/setting/tabs/subject.vue'

    const tab_value = ref('payee')
</script>
<style lang="stylus"></style>
