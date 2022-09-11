<template>
    <n-card title="复式记账">
        <n-tabs :default-value="tab_value" size="large" justify-content="space-evenly" @update:value="(value) => (tab_value = value)">
            <n-tab v-for="(label, name) in tabs" :name="name">{{ label }}</n-tab>
        </n-tabs>

        <n-form ref="form" require-mark-placement="right-hanging" :model="form_model" :rules="form_rules">
            <n-form-item-row label="帐号" path="username">
                <n-input v-model:value="form_model.username" placeholder="登录帐号" maxlength="30" clearable />
            </n-form-item-row>
            <n-form-item-row label="密码" path="password">
                <n-input v-model:value="form_model.password" placeholder="登录密码" maxlength="30" type="password" show-password-on="click" clearable />
            </n-form-item-row>
            <n-form-item-row label="邮箱" path="email" v-if="tab_value === TAB_SIGN_REG">
                <n-auto-complete v-model:value="form_model.email" placeholder="找回密码、接收账单等" maxlength="50" :options="form_email_options" type="email" clearable />
            </n-form-item-row>

            <n-button attr-type="submit" type="info" block secondary strong :loading="form_loading" @click="formSubmit">{{ tabs[tab_value] }}</n-button>
        </n-form>
    </n-card>
</template>
<script lang="ts" setup>
    import fly from '@wap/utils/fly'
    import { useRouter, useRoute } from 'vue-router'
    import { useStore } from 'vuex'
    import { computed, ref } from 'vue'

    const router = useRouter(),
        route = useRoute(),
        store = useStore()

    const TAB_SIGN_IN = 'sign_in',
        TAB_SIGN_REG = 'sign_reg',
        tabs = {
            [TAB_SIGN_IN]: '登录',
            [TAB_SIGN_REG]: '注册',
        },
        tab_value = ref(TAB_SIGN_IN)

    const form: any = ref('form'),
        form_model = ref({
            username: null,
            password: null,
            email: '',
        }),
        form_rules = {
            username: {
                required: true,
                trigger: ['blur', 'input'],
                message: '请输入登录帐号',
            },
            password: {
                required: true,
                trigger: ['blur', 'input'],
                message: '请输入登录密码',
            },
            email: {
                required: true,
                trigger: ['blur', 'input'],
                // https://stackoverflow.com/a/201447/6686061
                validator: (rule, value) => /^\S+@\S+\.\S+$/.test(value),
                message: '请输入邮箱',
            },
        },
        form_email_options = computed(() => {
            return ['@gmail.com', '@163.com', '@126.com', '@qq.com'].map((suffix) => {
                const prefix = form_model.value.email.split('@')[0]
                return {
                    label: prefix + suffix,
                    value: prefix + suffix,
                }
            })
        }),
        form_loading = ref(false),
        formSubmit = () => {
            form.value.validate((error) => {
                if (error) {
                    return
                }

                let is_sign_in = tab_value.value == TAB_SIGN_IN

                form_loading.value = true
                fly.post(`/sign/${is_sign_in ? 'in' : 'reg'}`, form_model.value)
                    .then((res: any) => {
                        store.state.naive.message.success(is_sign_in ? `欢迎回来 ${res.username}` : '注册成功')
                        store.commit('setToken', res.token)
                        router.replace({
                            name: 'Index',
                        })
                    })
                    .finally(() => {
                        form_loading.value = false
                    })
            })
        }
</script>
<style lang="stylus"></style>
