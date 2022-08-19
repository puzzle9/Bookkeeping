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
            redirect: '/book/account/lists',
            // component: () => import('@wap/views/index.vue'),
        },
        {
            path: '/book',
            name: 'Book',
            component: RouterComponent,
            children: [
                {
                    path: 'account',
                    name: 'BookAccount',
                    component: RouterComponent,
                    children: [
                        {
                            path: 'lists',
                            name: 'BookAccountLists',
                            component: () => import('@wap/views/book/account/lists.vue'),
                        },
                        {
                            path: 'createOrUpdate',
                            name: 'BookAccountCreateOrUpdate',
                            component: () => import('@wap/views/book/account/createOrUpdate.vue'),
                        },
                    ],
                },
            ],
        },
        {
            path: '/bill/:bill_id',
            name: 'Bill',
            component: RouterComponent,
            children: [
                {
                    path: '',
                    name: 'BillIndex',
                    component: () => import('@wap/views/bill/index.vue'),
                },
                {
                    path: 'createOrUpdate',
                    name: 'BillCreateOrUpdate',
                    component: () => import('@wap/views/bill/lists/createOrUpdate.vue'),
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
