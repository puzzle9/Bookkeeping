<template>
    <n-button type="info" block @click="modalCreate()">新增{{ subject_title }}</n-button>

    <template v-for="(data, type) in list_datas" :key="type">
        <n-divider title-placement="left">{{ types[type] }}</n-divider>
        <n-data-table :columns="list_data_columns" :data="data" default-expand-all :row-key="(row) => row.id" :scroll-x="400" />
    </template>

    <n-modal v-model:show="modal_show" :mask-closable="false">
        <n-card :title="`${form_model.id ? '编辑' : '新增'} ${subject_title}`" style="width: 90%" :bordered="false" aria-modal="true" closable @close="modal_show = false">
            <n-form ref="form" require-mark-placement="right-hanging" label-placement="top" label-width="auto" :model="form_model" :rules="form_rules">
                <n-form-item label="类型" path="type">
                    <n-radio-group v-model:value="form_model.type" @change="formDataParentSearch()">
                        <n-space>
                            <n-radio v-for="(name, type) in types" :key="type" :value="type">
                                {{ name }}
                            </n-radio>
                        </n-space>
                    </n-radio-group>
                </n-form-item>
                <n-form-item label="分组" path="parent_id">
                    <n-select v-model:value="form_model.parent_id" filterable placeholder="选择分组" :options="form_data_parent_options" :loading="form_data_parent_loading" />
                </n-form-item>
                <n-form-item label="名称" path="name">
                    <n-input
                        v-model:value="form_model.name"
                        type="text"
                        :placeholder="subject_name == 'type' ? '类别名称 如 用膳/早膳、夜宵，翻牌子等' : '账户名称 如 私房钱、汗水钱'"
                        clearable
                    ></n-input>
                </n-form-item>
                <n-form-item label="货币" path="bill_currency_id">
                    <n-select v-model:value="form_model.bill_currency_id" placeholder="选择货币" :options="currency" />
                </n-form-item>
                <n-form-item label="备注" path="remark">
                    <n-input
                        v-model:value="form_model.remark"
                        :autosize="{
                            minRows: 2,
                        }"
                        type="textarea"
                        :placeholder="`${subject_title}备注`"
                        :maxlength="200"
                        show-count
                        clearable
                    ></n-input>
                </n-form-item>
            </n-form>
            <template #action>
                <n-button style="float: right" type="primary" @click="modalFormSubmit">保存</n-button>
            </template>
        </n-card>
    </n-modal>
</template>
<script lang="ts" setup>
    import { BrushOutline, TrashOutline } from '@vicons/ionicons5'
    import { NButton, NIcon } from 'naive-ui'

    import fly from '@wap/utils/fly'
    import { useRouter, useRoute } from 'vue-router'
    import { useStore } from 'vuex'
    import { h, ref, computed } from 'vue'

    const { subject_name, subject_title } = defineProps({
        subject_name: String,
        subject_title: String,
    })

    const router = useRouter(),
        route = useRoute(),
        store = useStore()

    const subject_types = {
            type: {
                expenses: '支出',
                income: '收入',
            },
            account: {
                asset: '资产',
                liabilities: '负债',
            },
        },
        types = computed(() => subject_types[subject_name])

    const currency = ref([])

    fly.get('/bill/{bill_id}/setting/currency').then((res: any) => {
        currency.value = res.map((row) => {
            return {
                label: row.name,
                value: row.id,
            }
        })
        // modalCreate()
    })

    const list_datas = ref({}),
        listDatasGet = () => {
            // list_datas.value = {}
            fly.get('/bill/{bill_id}/setting/subject', {
                types: Object.keys(types.value),
            }).then((res: any) => {
                list_datas.value = res
            })
        },
        list_data_columns = [
            {
                key: 'name',
                title: '名称',
                width: 120,
                fixed: 'left',
            },
            {
                key: 'bill_currency_id',
                title: '货币',
                render: (row) => currency.value.find((data) => data.value == row.bill_currency_id)?.label,
                width: 100,
            },
            // {
            //     key: 'balance',
            //     title: '金额',
            // },
            {
                key: 'remark',
                title: '备注',
                // width: 100,
                ellipsis: {
                    tooltip: true,
                },
            },
            {
                title: '操作',
                key: 'actions',
                width: 85,
                fixed: 'right',
                render: (row) =>
                    h('div', [
                        h(
                            NButton,
                            {
                                text: true,
                                onClick: () => modalEdit(row),
                            },
                            {
                                default: () =>
                                    h(NIcon, {
                                        component: BrushOutline,
                                        size: 25,
                                    }),
                            },
                        ),
                        !row.children?.length
                            ? h(
                                  NButton,
                                  {
                                      style: {
                                          marginLeft: '10px',
                                      },
                                      text: true,
                                      onClick: () => modalDelete(row),
                                  },
                                  {
                                      default: () =>
                                          h(NIcon, {
                                              component: TrashOutline,
                                              size: 25,
                                          }),
                                  },
                              )
                            : null,
                    ]),
            },
        ]

    listDatasGet()

    const modal_show = ref(false),
        form: any = ref('form'),
        form_model = ref({
            id: 0,
            name: null,
            type: Object.keys(types.value)[0],
            bill_currency_id: null,
            parent_id: 0,
            remark: null,
        }),
        form_rules = {
            name: {
                required: true,
                trigger: ['blur', 'input'],
                message: '',
            },
            bill_currency_id: {
                type: 'number',
                required: true,
                trigger: ['blur', 'change'],
                message: '',
            },
        },
        form_data_parent_loading = ref(false),
        form_data_parent_options: any = ref([]),
        formDataParentSearch = (value_id = 0) => {
            form_data_parent_loading.value = true
            // todo: 缓存处理
            fly.get('/bill/{bill_id}/setting/subject/parent', {
                type: form_model.value.type,
            }).then((res: any) => {
                let parents = res.map((row) => {
                    return {
                        label: row.name,
                        value: row.id,
                    }
                })

                form_data_parent_options.value = [
                    {
                        label: '新建或当前分组',
                        value: 0,
                    },
                    ...parents,
                ]

                form_data_parent_loading.value = false
                form_model.value.parent_id = value_id
            })
        },
        modalCreate = () => {
            formDataParentSearch()
            form_model.value.id = 0
            form_model.value.name = null
            form_model.value.remark = null
            form_model.value.bill_currency_id = currency.value[0]?.value
            modal_show.value = true
        },
        modalEdit = (row) => {
            let data = Object.assign({}, row)
            delete data.children
            form_model.value = data
            formDataParentSearch(data.parent_id)
            modal_show.value = true
        },
        modalDelete = (row) => {
            store.state.naive.dialog.warning({
                title: `确认删除 ${row.name}?`,
                content: '已有账单依旧可以正常查询',
                positiveText: '确定',
                negativeText: '取消',
                onPositiveClick: () => {
                    fly.delete('/bill/{bill_id}/setting/subject', {
                        id: row.id,
                    }).then((res) => {
                        store.state.naive.message.success('删除成功')

                        listDatasGet()
                    })
                },
            })
        },
        modalFormSubmit = () => {
            form.value?.validate((errors) => {
                if (errors) {
                    return
                }

                fly.post('/bill/{bill_id}/setting/subject', form_model.value).then((res) => {
                    store.state.naive.message.success('保存成功')
                    listDatasGet()
                    modal_show.value = false
                })
            })
        }
</script>
<style lang="stylus"></style>
