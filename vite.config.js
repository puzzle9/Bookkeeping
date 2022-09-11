import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'
import { VitePWA } from 'vite-plugin-pwa'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/assets/wap/main.ts'],
            refresh: true,
        }),
        vue(),
        VitePWA({
            injectRegister: 'inline',
            manifest: {
                lang: 'zh-CN',
                name: 'bookkeeping',
                short_name: '记账',
                description: '一款复式记账的记账',
                start_url: '/',
                scope: '/',
                theme_color: '#ffffff',
                background_color: '#ffffff',
                display: 'standalone',
                icons: [
                    {
                        src: '/favicon/android-chrome-192x192.png',
                        sizes: '192x192',
                        type: 'image/png',
                    },
                    {
                        src: '/favicon/android-chrome-512x512.png',
                        sizes: '512x512',
                        type: 'image/png',
                    },
                ],
            },
        }),
    ],
    resolve: {
        alias: {
            '@wap': '/resources/assets/wap/',
        },
    },
})
