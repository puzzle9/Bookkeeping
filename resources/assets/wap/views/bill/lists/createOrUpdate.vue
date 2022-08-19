<template>
    <NavBar :title="title"></NavBar>

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
            <n-form-item label="来源/支出" path="book_account_id">
                <n-select v-model:value="form_model.book_account_id" placeholder="从哪里来的或要到哪里去的 名称" filterable tag clearable :options="form_data.book_account_ids" />
            </n-form-item>

            <n-form-item-row label="附件" path="files">
                <n-upload :default-file-list="form_model.files" list-type="image-card" :max="3" accept="image/*" :custom-request="form_data_files_upload" @remove="form_data_files_delete">
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

            <n-form-item>
                <n-button attr-type="button" @click="formSubmit">验证</n-button>
            </n-form-item>
        </n-form>
    </Layout>
</template>
<script lang="ts" setup>
    import fly from '@wap/utils/fly'
    import dayjs from '@wap/utils/dayjs'
    import { ref } from 'vue'
    import { useRouter, useRoute } from 'vue-router'
    import { UploadCustomRequestOptions } from 'naive-ui'

    const router = useRouter(),
        route = useRoute()

    const title = ref('加载中'),
        loading = ref(true)

    const info_id = route.query.id
    if (info_id) {
    } else {
        title.value = '记账'
        loading.value = false
    }

    const form = ref('form'),
        form_data = ref({
            book_account_ids: [],
        }),
        form_model = ref({
            time_occurrence: dayjs().format('YYYY-MM-DD HH:mm'),
            book_account_id: null,
            files: [],
            remark: null,
        }),
        form_rules = {
            time_occurrence: {
                required: true,
                trigger: ['blur', 'input'],
                message: '记账日期呀呀呀',
            },
            book_account_id: {
                required: true,
                trigger: ['blur', 'input'],
                message: '来源 如 尊敬的老婆、那个老板, 支出 如 街角那家咖啡馆、那个大蛋糕, 一个自己晓得的人名、地名或标记等',
            },
        },
        form_data_files_upload = (options: UploadCustomRequestOptions) => {
            let formData = new FormData()
            // @ts-ignore
            formData.append('file', options.file.file)
            let file = fly.post('/file', formData)
            // @ts-ignore
            file.engine.upload.onprogress = (e) => {
                options.onProgress({
                    percent: Number(((e.loaded / e.total) * 100).toFixed(0)),
                })
            }

            file.then((res) => {
                options.onFinish()
            }).catch((err) => {
                options.onError()
            })
        },
        form_data_files_delete = (options) => {
            // todo: 文件删除逻辑
            return true
        },
        formSubmit = () => {
            console.log(33)
        }
</script>
<style lang="stylus"></style>
