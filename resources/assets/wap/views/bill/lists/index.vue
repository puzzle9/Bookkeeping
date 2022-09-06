<template>
    <div class="create">
        <div class="button">
            <n-icon size="50" :component="AddCircleOutline" @click="toCreateOrUpdate()" />
        </div>
    </div>

    <Layout>
        <div class="search">
            <n-date-picker v-model:value="search.date" type="date" :first-day-of-week="6" @update:value="getData" clearable />
            <br />
            <n-select v-model:value="search.bill_payee_id" placeholder="收款者" :options="store.state.bill_base.payees" @update:value="getData" filterable clearable />
        </div>

        <n-spin v-if="loading" class="loading" />
        <template v-else>
            <template v-if="data.length">
                <n-divider title-placement="center" dashed>
                    <n-tag :bordered="false" type="info">{{ data.length }} 条记录 共计: {{ amount }}￥</n-tag>
                </n-divider>
                <n-list class="lists">
                    <n-list-item v-for="row in data" :key="row.id">
                        <n-thing :title="`${row.time_occurrence} ${row.payee}`" :description="row.remark">
                            <template #header-extra>
                                <n-button quaternary type="info" @click="toCreateOrUpdate(row.id)">￥{{ row.amount }}</n-button>
                            </template>
                            <n-descriptions label-placement="left" :column="1" bordered>
                                <n-descriptions-item v-for="info in row.infos" :key="info.info_id">
                                    <template #label>{{ info.subject_type_string }} - {{ info.subject_name }}</template>
                                    {{ info.current_amount }}￥
                                </n-descriptions-item>
                            </n-descriptions>
                            <template v-if="row.files.length">
                                <br />
                                <n-upload :default-file-list="row.files" list-type="image-card" :max="row.files.length" accept="image/*" :show-remove-button="false" />
                            </template>
                        </n-thing>
                    </n-list-item>
                </n-list>
            </template>
            <template v-else>
                <br />
                <n-empty description="空也"></n-empty>
            </template>
        </template>
    </Layout>
</template>
<script lang="ts" setup>
    import dayjs from '@wap/utils/dayjs'
    import fly from '@wap/utils/fly'
    import { useRouter, useRoute } from 'vue-router'
    import { AddCircleOutline } from '@vicons/ionicons5'
    import { ref, computed } from 'vue'
    import { useStore } from 'vuex'

    const router = useRouter(),
        route = useRoute(),
        store = useStore()

    store.dispatch('getBillBase')

    const toCreateOrUpdate = (id = null) => {
        router.push({
            name: 'BillCreateOrUpdate',
            query: {
                id,
            },
        })
    }

    const loading = ref(false),
        search = ref({
            date: dayjs().valueOf(),
            bill_payee_id: null,
        }),
        data = ref([]),
        amount = computed(() =>
            data.value
                .map((row) => row.amount)
                .reduce((sum, value) => sum + value, 0)
                .toFixed(2),
        ),
        getData = () => {
            loading.value = true
            data.value = []

            let query_search = {}
            for (let search_key in search.value) {
                let search_value = search.value[search_key]
                if (search_value) {
                    switch (search_key) {
                        case 'date':
                            search_value = dayjs(search_value).format('YYYY-MM-DD')
                            break
                        default:
                            break
                    }

                    query_search[search_key] = search_value
                }
            }

            if (!Object.keys(query_search).length) {
                store.state.naive.message.info('未有搜索条件 暂只展示50条数据')
                query_search['take'] = 50
            }

            fly.get('/bill/{bill_id}/', {
                search: query_search,
            }).then((res: any) => {
                data.value = res.map((row) => {
                    row.time_occurrence = dayjs(row.time_occurrence).format('YYYY-MM-DD HH:mm')
                    row.files = row.files.map((file) => {
                        return {
                            url: file,
                            thumbnailUrl: `${file}?thumbnail`,
                            status: 'finished',
                        }
                    })
                    return row
                })
                loading.value = false
            })
        }

    getData()
</script>
<style lang="stylus">
    .create
        .button
            z-index 1
            position: fixed
            bottom: 50px
            right: 50px
</style>
