import { createApp } from 'vue'
import App from '@wap/App.vue'

const app = createApp(App)

import router from '@wap/router'
import store from '@wap/store'

app.use(router)
app.use(store)

// vant
import Vant from 'vant'
import 'vant/lib/index.css'

app.use(Vant)

app.mount('#app')
