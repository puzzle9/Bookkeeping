<template>
    <n-config-provider :locale="zhCN" :date-locale="dateZhCN" :theme="theme">
        <router-view></router-view>
    </n-config-provider>
</template>

<script setup lang="ts">
    import { zhCN, dateZhCN, useOsTheme, lightTheme, darkTheme } from 'naive-ui'
    import { ref, computed } from 'vue'
    import { useStore } from 'vuex'

    const store = useStore()

    const theme_os = useOsTheme(),
        theme_default = ref(null),
        theme = computed(() => {
            let background_color = null,
                theme = null,
                theme_name = theme_default.value || store.state.theme || theme_os.value

            if (theme_name == 'light') {
                background_color = '#f7f8fa'
                theme = lightTheme
            } else {
                background_color = 'black'
                theme = darkTheme
            }

            // https://stackoverflow.com/a/49195596/6686061
            document.body.style['background-color'] = background_color
            return theme
        })

    // theme_default.value = 'dark'
</script>

<style></style>
