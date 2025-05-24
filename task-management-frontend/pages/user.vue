<script setup lang="ts">
import { useRouter } from 'vue-router'
import { useUser } from '~/composables/useUser'
import { useStore } from '~/stores/useStore'
import { useForm, useField } from 'vee-validate'
import * as yup from 'yup'
import { useCategory } from '~/composables/useCategory'
import { watch } from 'vue'

const store = useStore()
const router = useRouter()

const schema = yup.object({
    name: yup.string().required('Name is required'),
    email: yup.string().email('Invalid email').required('Email is required'),
    password: yup.string().required('Password is required')
})

const { handleSubmit, errors, setValues } = useForm({
    validationSchema: schema,
    initialValues: {
        name: '',
        email: '',
        password: ''
    }
})

const { value: name, meta: metaName } = useField('name')
const { value: email, meta: metaEmail } = useField('email')
const { value: password, meta: metaPassword } = useField('password')

const errorMessage = ref('')

watch(
    () => store.user,
    (newUser) => {
        if (newUser) {
            setValues({
                name: newUser.name,
                email: newUser.email,
                password: ''
            })
        }
    },
    { immediate: true }
)

const submit = handleSubmit(async (values) => {
    errorMessage.value = ''
    try {
        if (store.user) {
            await useUser.update(values)
        } else {
            await useUser.register(values)
            const categories = await useCategory.fetchAll()
            store.setCategories(categories)
        }
        router.push('/tasks')
    } catch (error: any) {
        if (error.data) {
            if (typeof error.data === 'string') {
                errorMessage.value = error.data
            } else {
                try {
                    errorMessage.value = Object.values(error.data).flat().join(' ')
                } catch {
                    errorMessage.value = JSON.stringify(error.data)
                }
            }
        } else {
            errorMessage.value = error.message
        }
    }
})
</script>

<template>
    <form @submit.prevent="submit" class="max-w-md mx-auto p-4 space-y-4">
        <h2 class="text-2xl font-bold mb-6">User</h2>

        <div>
            <label class="block font-semibold">Name</label>
            <input v-model="name" type="text"
                :class="['w-full p-2 border rounded', errors.name && metaName.touched ? 'border-red-500' : '']" />
            <div v-if="errors.name && metaName.touched" class="text-red-500 text-sm">{{ errors.name }}</div>
        </div>

        <div>
            <label class="block font-semibold">Email</label>
            <input v-model="email" type="email"
                :class="['w-full p-2 border rounded', errors.email && metaEmail.touched ? 'border-red-500' : '']" />
            <div v-if="errors.email && metaEmail.touched" class="text-red-500 text-sm">{{ errors.email }}</div>
        </div>

        <div>
            <label class="block font-semibold">Password</label>
            <input v-model="password" type="password"
                :class="['w-full p-2 border rounded', errors.password && metaPassword.touched ? 'border-red-500' : '']" />
            <div v-if="errors.password && metaPassword.touched" class="text-red-500 text-sm">{{ errors.password }}</div>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded">
            Save
        </button>

        <div v-if="errorMessage" class="text-red-500 text-sm mt-2">
            {{ errorMessage }}
        </div>
    </form>
</template>
