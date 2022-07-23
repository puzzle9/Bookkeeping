import { createRouter, createWebHashHistory } from 'vue-router'

import RouterComponent from '@wap/components/RouterComponent.vue'

const ROUTE_NAME_SIGN = 'Sign'

const router = createRouter({
    history: createWebHashHistory(),
    scrollBehavior(to, from, savedPosition) {
        return savedPosition || { left: 0, top: 0 }
    },
    routes: [
        {
            path: '/',
            name: 'Index',
            component: () => import('@wap/views/index.vue'),
        },
        {
            path: '/sign',
            name: ROUTE_NAME_SIGN,
            component: () => import('@wap/views/sign.vue'),
        },
    ],
})

export default router
