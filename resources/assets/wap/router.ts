import store from '@wap/store'

import { createRouter, createWebHashHistory } from 'vue-router'

import RouterComponent from '@wap/components/RouterComponent.vue'

export const ROUTE_NAME_SIGN = 'Sign'

const router = createRouter({
    history: createWebHashHistory(),
    scrollBehavior(to, from, savedPosition) {
        return savedPosition || { left: 0, top: 0 }
    },
    routes: [
        {
            path: '/sign',
            name: ROUTE_NAME_SIGN,
            component: () => import('@wap/views/sign.vue'),
        },

        {
            path: '/',
            name: 'Index',
            redirect: '/bill/lists',
            // component: () => import('@wap/views/index.vue'),
        },
        {
            path: '/bill',
            name: 'Bill',
            component: RouterComponent,
            children: [
                {
                    path: 'lists',
                    name: 'BillLists',
                    component: () => import('@wap/views/bill/lists.vue'),
                },
                {
                    path: 'createOrUpdate',
                    name: 'BillCreateOrUpdate',
                    component: () => import('@wap/views/bill/createOrUpdate.vue'),
                },
            ],
        },
    ],
})

router.beforeEach(async (to, from, next) => {
    if (!store.state.token && to.name != ROUTE_NAME_SIGN) {
        await router.push({
            name: ROUTE_NAME_SIGN,
        })
    }

    next()
})

export default router
