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
            bill_base: {
                payees: [],
                subjects: [],
            },
            account: {
                name: '账本',
            },
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
        setBillBase(state, bill_base) {
            state.bill_base = bill_base
        },
        setAccount(state, account) {
            state.account = account
        },
    },
    actions: {
        async getBillBase() {
            let res: any = await fly.get('/bill/{bill_id}/base')

            let payees = res.payees.map((row) => {
                let remark = row.remark
                return {
                    label: row.name + (remark ? ' - ' + remark : ''),
                    value: row.id,
                }
            })

            let subjects = []
            for (let subject of res.subjects) {
                for (let data of subject.data) {
                    let children = data.children.map((row) => {
                        return {
                            label: row.name,
                            value: row.id,
                        }
                    })
                    subjects.push({
                        type: 'group',
                        label: `${subject.type_string} / ${data.name}`,
                        key: `${subject.type}_${data.id}`,
                        children,
                    })
                }
            }
            this.commit('setBillBase', {
                payees,
                subjects,
            })
        },
    },
    getters: {},
})

export default store
