import dayjs from 'dayjs'

import zhCN from 'dayjs/locale/zh-cn'
dayjs.locale(zhCN)

import relativeTime from 'dayjs/plugin/relativeTime'
dayjs.extend(relativeTime)

export default dayjs
