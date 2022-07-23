import router from '@wap/router'
import fly from 'flyio'

fly.config.headers.Accept = 'application/json'
fly.config.timeout = 10000
fly.config.baseURL = '/api'

fly.interceptors.request.use((request) => {
    return request
})

fly.interceptors.response.use(
    (res) => {
        return res.data
    },
    (err: any) => {
        switch (err.code) {
        }
        return err
    },
)

export default fly
