<template>
    <NavBar :title="title">
        <template #extra v-if="form_model.id">
            <n-icon class="setting" size="22" :component="TrashOutline" @click="showDelete" />
        </template>
    </NavBar>

    <Layout :loading="loading">
        <n-form ref="form" require-mark-placement="right-hanging" label-placement="top" label-width="auto" :model="form_model" :rules="form_rules">
            <n-form-item label="账单时间" path="time_occurrence">
                <n-date-picker
                    v-model:formatted-value="form_model.time_occurrence"
                    type="datetime"
                    placeholder="请选择交易时间"
                    value-format="yyyy-MM-dd HH:mm"
                    format="yyyy-MM-dd HH:mm"
                    :time-picker-props="{
                        format: 'HH:mm',
                    }"
                    :first-day-of-week="6"
                    clearable
                    input-readonly
                    style="width: 100%"
                />
            </n-form-item>
            <n-form-item label="来源/支出" path="bill_payee_id">
                <n-select v-model:value="form_model.bill_payee_id" placeholder="从哪里来的或要到哪里去的 名称" filterable tag clearable :options="store.state.bill_base.payees" />
            </n-form-item>

            <n-form-item-row label="附件">
                <n-upload :default-file-list="form_model_files" list-type="image-card" :max="3" accept="image/*" :custom-request="form_data_files_upload" @remove="form_data_files_delete">
                    点击上传
                </n-upload>
            </n-form-item-row>

            <n-form-item label="备注" path="remark">
                <n-input
                    v-model:value="form_model.remark"
                    :autosize="{
                        minRows: 2,
                    }"
                    type="textarea"
                    placeholder="账单备注、描述等"
                    :maxlength="200"
                    show-count
                    clearable
                ></n-input>
            </n-form-item>

            <div class="lists">
                <n-divider dashed>账户明细</n-divider>

                <template v-for="(list, index) in form_model.lists" :key="index">
                    <n-form-item
                        label="账户"
                        :path="`lists[${index}].bill_subject_id`"
                        :rule="{
                            type: 'number',
                            required: true,
                            message: '请选择账户',
                            trigger: ['blur', 'change'],
                        }"
                    >
                        <n-select v-model:value="list.bill_subject_id" placeholder="请选择账户" :options="store.state.bill_base.subjects" filterable />
                    </n-form-item>
                    <n-form-item
                        label="金额"
                        :path="`lists[${index}].current_amount`"
                        :rule="{
                            type: 'number',
                            required: true,
                            message: `请输入金额`,
                            trigger: ['input', 'blur'],
                        }"
                    >
                        <n-input-number class="amount" v-model:value="list.current_amount" placeholder="请输入金额" clearable :show-button="false" :precision="2">
                            <template #prefix>￥</template>
                        </n-input-number>
                    </n-form-item>
                    <n-form-item label="备注" path="remark">
                        <n-input
                            v-model:value="list.remark"
                            :autosize="{
                                minRows: 1,
                            }"
                            type="textarea"
                            placeholder="交易备注"
                            :maxlength="200"
                            show-count
                            clearable
                        ></n-input>

                        <n-button class="remove" text @click="listRemove(index)">
                            <n-icon :component="CloseCircleOutline" />
                        </n-button>
                    </n-form-item>
                </template>

                <n-button dashed block @click="listAdd()">
                    <template #icon>
                        <n-icon :component="AddOutline" />
                    </template>
                    添加帐目
                </n-button>
            </div>

            <n-form-item>
                <n-button type="primary" block attr-type="button" @click="formSubmit">保存</n-button>
            </n-form-item>
        </n-form>
    </Layout>
</template>
<script lang="ts" setup>
    import fly from '@wap/utils/fly'
    import dayjs from '@wap/utils/dayjs'
    import Compressor from 'compressorjs'
    import { ref, computed } from 'vue'
    import { useRouter, useRoute } from 'vue-router'
    import { useStore } from 'vuex'
    import { UploadCustomRequestOptions } from 'naive-ui'
    import { AddOutline, CloseCircleOutline, TrashOutline } from '@vicons/ionicons5'

    const router = useRouter(),
        route = useRoute(),
        store = useStore()

    const title = ref('加载中'),
        loading = ref(true)

    const info_id = route.query.id
    if (info_id) {
        fly.get(`/bill/{bill_id}/${info_id}`).then((res: any) => {
            title.value = '编辑'
            loading.value = false

            form_model_files.value = res.files.map((file) => {
                return {
                    url: file,
                    // thumbnailUrl: `${file}?thumbnail`,
                    status: 'finished',
                }
            })

            form_model.value = {
                id: res.id,
                time_occurrence: dayjs(res.time_occurrence).format('YYYY-MM-DD HH:mm'),
                bill_payee_id: res.bill_payee_id,
                files: res.original_files,
                remark: res.remark,
                lists: res.infos_simple.map((info) => {
                    return {
                        id: info.id,
                        bill_subject_id: info.bill_subject_id,
                        current_amount: parseFloat(info.current_amount),
                        remark: info.remark,
                    }
                }),
            }
        })
    } else {
        title.value = '记账'
        loading.value = false
    }

    const form: any = ref('form'),
        form_model_files = ref([]),
        form_model = ref({
            id: null,
            time_occurrence: dayjs().format('YYYY-MM-DD HH:mm'),
            bill_payee_id: null,
            files: [],
            remark: null,
            lists: [],
        }),
        form_rules = {
            time_occurrence: {
                required: true,
                trigger: ['blur', 'input'],
                message: '记账日期呀呀呀',
            },
            bill_payee_id: {
                required: true,
                type: 'any',
                trigger: ['blur', 'input', 'change'],
                message: '来源 如 尊敬的老婆、那个老板, 支出 如 街角那家咖啡馆、那个大蛋糕, 一个自己晓得的人名、地名或标记等',
            },
        },
        form_data_files_upload = (options: UploadCustomRequestOptions) => {
            new Compressor(options.file.file, {
                strict: true,
                checkOrientation: false,
                // todo: 选项开启图片压缩功能
                quality: 0.8,
                // 1MB
                convertSize: 1000000,
                success(result: any) {
                    let formData = new FormData()
                    // @ts-ignore
                    formData.append('file', result)
                    let upload = fly.post('/file', formData)
                    // @ts-ignore
                    upload.engine.upload.onprogress = (e) => {
                        options.onProgress({
                            percent: Number(((e.loaded / e.total) * 100).toFixed(0)),
                        })
                    }

                    upload
                        .then((res: any) => {
                            form_model.value.files.push(res.path)
                            options.onFinish()
                        })
                        .catch((err) => {
                            options.onError()
                        })
                },
                error(error) {
                    options.onError()
                    console.log(error.message)
                },
            })
        },
        form_data_files_delete = (options) => {
            // todo: 文件删除逻辑
            return true
        },
        // https://stackoverflow.com/a/16751601/6686061
        list_amount = computed(() => form_model.value.lists.map((row) => row.current_amount).reduce((sum, value) => sum + value, 0)),
        listAdd = () => {
            form_model.value.lists.push({
                bill_subject_id: null,
                // current_bill_currency_id: null,
                current_amount: list_amount.value ? -list_amount.value : null,
                // transform_amount: null,
                remark: null,
            })
        },
        listRemove = (index) => {
            form_model.value.lists.splice(index, 1)
        },
        formSubmit = () => {
            form.value?.validate((errors) => {
                if (errors) {
                    return
                }

                let form_model_value = form_model.value
                if (!form_model_value.lists.length) {
                    store.state.naive.message.error('需添加 账户明细')
                    return
                }

                if (list_amount.value) {
                    store.state.naive.message.error(`帐目不平衡 ￥${-list_amount.value.toFixed(2)}`)
                    return
                }

                fly.post('/bill/{bill_id}/', form_model_value).then((res) => {
                    store.state.naive.message.success('保存成功')
                    router.back()
                })
            })
        },
        showDelete = () => {
            store.state.naive.dialog.warning({
                title: `确认删除 ${form_model.value.time_occurrence}?`,
                positiveText: '确定',
                negativeText: '取消',
                onPositiveClick: () => {
                    fly.delete('/bill/{bill_id}/', {
                        id: form_model.value.id,
                    }).then((res) => {
                        store.state.naive.message.success('删除成功')
                        router.back()
                    })
                },
            })
        }
</script>
<style lang="stylus">
    .lists
        .amount
            width 100%

        .remove
            font-size 30px
            margin-left 12px
</style>
