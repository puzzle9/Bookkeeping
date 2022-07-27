<template>
    <van-nav-bar :title="nav_title" left-text="返回" left-arrow @click-left="router.back()" />
    <van-skeleton :row="3" :loading="loading">
        <van-form>
            <van-cell-group inset>
                <van-field
                    v-model="form_data.time_occurrence_date"
                    is-link
                    readonly
                    label="交易日期"
                    @click="form_data_time_occurrence_date_show = true"
                    :rules="[{ required: true, message: '请选择账单日期' }]"
                />
                <van-calendar
                    v-model:show="form_data_time_occurrence_date_show"
                    :show-confirm="false"
                    @confirm="form_data_time_occurrence_date_confirm"
                    :min-date="form_data_time_occurrence_date_min"
                    :max-date="form_data_time_occurrence_date_max"
                />

                <van-field
                    v-model="form_data.time_occurrence_time"
                    is-link
                    readonly
                    label="交易时间"
                    @click="form_data_time_occurrence_time_show = true"
                    :rules="[{ required: true, message: '请选择账单日期' }]"
                />
                <van-popup v-model:show="form_data_time_occurrence_time_show" position="bottom">
                    <van-time-picker v-model="form_data_time_occurrence_time_default" :columns-type="['hour', 'minute']" title="选择时间" @confirm="form_data_time_occurrence_time_confirm" />
                </van-popup>

                <!-- todo: select 组件 支持新增-->
                <van-field
                    v-model="form_data.book_account_id"
                    label="来源/支出"
                    placeholder="从哪里来的或到哪里去的 名称"
                    :rules="[{ required: true, message: '来源 如 尊敬的老婆、那个老板, 支出 如 街角那家咖啡馆、那个大蛋糕, 一个自己晓得的人名、地名或标记等' }]"
                />

                <van-field label="附件">
                    <template #input>
                        <van-uploader v-model="form_data.files" type="file" accept="image/*" :max-count="5" :after-read="form_data_files_upload" :before-delete="form_data_files_delete" />
                    </template>
                </van-field>

                <van-field v-model="form_data.remark" rows="2" autosize label="备注" type="textarea" placeholder="账单备注、描述等" maxlength="200" show-word-limit />
            </van-cell-group>
            <div style="margin: 16px">
                <van-button round block type="primary" native-type="submit" @click="formSubmit">保存</van-button>
            </div>
        </van-form>
    </van-skeleton>
</template>
<script lang="ts" setup>
    import dayjs from 'dayjs'
    import { ref } from 'vue'
    import { useRouter, useRoute } from 'vue-router'

    const router = useRouter(),
        route = useRoute()

    const nav_title = ref('加载中'),
        loading = ref(true)

    const info_id = route.query.id
    if (info_id) {
    } else {
        nav_title.value = '记账'
        loading.value = false
    }

    const date = dayjs()
    const form_data = ref({
            time_occurrence_date: date.format('YYYY-MM-DD'),
            time_occurrence_time: date.add(1, 'minute').format('HH:mm'),
            book_account_id: null,
            files: [],
            remark: null,
            address: {
                name: '',
                longitude: '',
                latitude: '',
            },
        }),
        form_data_time_occurrence_date_show = ref(false),
        // todo： 最小 最大 动态设置
        form_data_time_occurrence_date_min = date.subtract(2, 'month').date(1).toDate(),
        form_data_time_occurrence_date_max = date.add(1, 'month').toDate(),
        form_data_time_occurrence_date_confirm = (value) => {
            form_data.value.time_occurrence_date = dayjs(value).format('YYYY-MM-DD')
            form_data_time_occurrence_date_show.value = false
        },
        form_data_time_occurrence_time_show = ref(false),
        // form_data_time_occurrence_time_default = [date.format('HH'), date.format('mm')],
        form_data_time_occurrence_time_default = form_data.value.time_occurrence_time.split(':'),
        form_data_time_occurrence_time_confirm = ({ selectedOptions }) => {
            form_data.value.time_occurrence_time = `${selectedOptions[0].value}:${selectedOptions[1].value}`
            form_data_time_occurrence_time_show.value = false
        },
        form_data_files_upload = (file, detail) => {
            file.status = 'uploading'
            file.status = 'done'
            file.message = '上传中'
            console.log(file, detail)
        },
        form_data_files_delete = (file, detail) => {
            // todo: 文件删除逻辑
            return true
        },
        formSubmit = () => {
            console.log('formSubmit')
        }
</script>
<style lang="stylus"></style>
