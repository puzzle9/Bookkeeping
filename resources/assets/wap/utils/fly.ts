import store from '@wap/store'
import router, { ROUTE_NAME_SIGN } from '@wap/router'
import fly from 'flyio'

fly.config.headers.Accept = 'application/json'
fly.config.timeout = 10000
fly.config.baseURL = '/api'

fly.interceptors.request.use((request) => {
    request.headers.Authorization = `Bearer ${store.state.token}`
    return request
})

fly.interceptors.response.use(
    (res) => {
        return res.data.data
    },
    (err: any) => {
        let naive_message = store.state.naive.message

        let status = err.status,
            message = err.response.data.message
        console.log(status, message)
        switch (err.status) {
            case 401:
                naive_message.warning('登录失效 正在返回登录页')
                store.commit('setToken')
                router.push({
                    name: ROUTE_NAME_SIGN,
                })
                break

            case 422:
                naive_message.error(message)
                break

            case 500:
                store.state.naive.dialog.error({
                    title: '服务器出现了问题',
                    positiveText: '啊',
                })
                break

            default:
                naive_message.error(`好像哪里出现了错误 - ${status}`)
                break
        }
        return err
    },
)

export default fly
