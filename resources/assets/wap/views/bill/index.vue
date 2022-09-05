<template>
    <NavBar :title="store.state.account.name">
        <template #extra>
            <n-icon class="setting" size="22" :component="component_name == 'ComponentSettings' ? vicons.SettingsSharp : vicons.SettingsOutline" @click="toComponent('ComponentSettings')" />
        </template>
    </NavBar>
    <component :is="components[component_name]"></component>
</template>
<script lang="ts" setup>
    import { useRouter, useRoute } from 'vue-router'
    import { useStore } from 'vuex'

    import * as vicons from '@vicons/ionicons5'

    import ComponentLists from '@wap/views/bill/lists/index.vue'
    import ComponentSettings from '@wap/views/bill/setting/index.vue'
    import { ref } from 'vue'

    const router = useRouter(),
        route = useRoute(),
        store = useStore()

    const components = {
            ComponentLists,
            ComponentSettings,
        },
        component_default = 'ComponentLists',
        component_name = ref('ComponentSettings')

    const toComponent = (name) => {
        component_name.value = component_name.value == name ? component_default : name
    }
</script>
<style lang="stylus">
    .setting
        display flex
</style>
