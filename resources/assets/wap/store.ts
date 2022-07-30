import fly from '@wap/utils/fly'
import router from '@wap/router'
import { createStore } from 'vuex'
import { createDiscreteApi } from 'naive-ui'

import * as storage from '@wap/utils/storage'

const store = createStore({
    state() {
        return {
            // todo: fix createDiscreteApi theme
            naive: createDiscreteApi(['message', 'dialog', 'notification', 'loadingBar']),
            token: storage.storageTokenGet(),
            theme: storage.storageThemeGet(),
            base: {},
        }
    },
    mutations: {
        setToken(state, token = null) {
            state.token = token
            storage.storageTokenSet(token)
        },
        setTheme(state, theme) {
            state.theme = theme
            storage.storageThemeSet(theme)
        },
        setBase(state, base) {
            state.base = base
        },
    },
    actions: {
        // 每次重新进入记账页面用到
        async getBillBase() {
            let data = await fly.get('/bill/base')
            this.commit('setBase', data)
        },
    },
    getters: {},
})

export default store
