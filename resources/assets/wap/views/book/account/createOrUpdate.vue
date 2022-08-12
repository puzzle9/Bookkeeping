<template>
    <NavBar :title="title"></NavBar>

    <Layout :loading="loading">
        <n-form ref="form" require-mark-placement="right-hanging" label-placement="top" label-width="auto" :model="form_model" :rules="form_rules">
            <n-form-item label="账本名称" path="name">
                <n-input v-model:value="form_model.name" placeholder="账本名称 账本之间数据无法互通" :maxlength="10" show-count clearable></n-input>
            </n-form-item>

            <n-form-item label="备注" path="remark">
                <n-input
                    v-model:value="form_model.remark"
                    :autosize="{
                        minRows: 2,
                    }"
                    type="textarea"
                    placeholder="账本备注、描述等"
                    :maxlength="200"
                    show-count
                    clearable
                ></n-input>
            </n-form-item>

            <n-form-item>
                <n-button block attr-type="submit" type="info" @click="formSubmit" :loading="form_loading">保存</n-button>
            </n-form-item>
        </n-form>
    </Layout>
</template>
<script lang="ts" setup>
    import fly from '@wap/utils/fly'
    import { ref } from 'vue'
    import { useRouter, useRoute } from 'vue-router'
    import { useStore } from 'vuex'

    const router = useRouter(),
        route = useRoute(),
        store = useStore()

    const title = ref('加载中'),
        loading = ref(true)

    const info_id = route.query.id
    if (info_id) {
    } else {
        title.value = '新建账本'
        loading.value = false
    }

    const form: any = ref('form'),
        form_model = ref({
            id: 0,
            name: null,
            remark: null,
        }),
        form_rules = {
            name: {
                required: true,
                trigger: ['blur', 'input'],
                message: '账本名称呀呀呀',
            },
        },
        form_loading = ref(false),
        formSubmit = () => {
            form.value.validate((error) => {
                if (error) {
                    return
                }

                form_loading.value = true
                fly.post('/book/account', form_model.value)
                    .then((res: any) => {
                        store.state.naive.message.success('保存成功')
                        router.push({
                            name: 'BookAccountLists',
                        })
                    })
                    .finally(() => {
                        form_loading.value = false
                    })
            })
        }
</script>
<style lang="stylus"></style>
