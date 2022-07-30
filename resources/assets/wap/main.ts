import { createApp } from 'vue'
import App from '@wap/App.vue'

const app = createApp(App)

import router from '@wap/router'
import store from '@wap/store'

app.use(router)
app.use(store)

import naive from 'naive-ui'

app.use(naive)

import components from '@wap/components'

app.use(components)

app.mount('#app')
