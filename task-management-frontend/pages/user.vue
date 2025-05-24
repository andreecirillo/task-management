<script setup lang="ts">
import { useRouter } from 'vue-router'
import { useUser } from '~/composables/useUser'
import { useStore } from '~/stores/useStore'
import { useForm, useField } from 'vee-validate'
import * as yup from 'yup'
import { useCategory } from '~/composables/useCategory'
import { watch, computed, ref } from 'vue'

const store = useStore()
const router = useRouter()

const isRegister = computed(() => !store.user)

const schema = computed(() => {
    const base = {
        name: yup.string().required('Name is required'),
        email: yup.string().email('Invalid email').required('Email is required'),
        password: yup.string().required(isRegister.value ? 'Password is required' : 'Current password is required'),
        new_password: yup.string().nullable(),
        new_password_confirmation: yup.string().when('new_password', {
            is: (val: string) => !!val,
            then: s => s.required('New password confirmation is required')
                .oneOf([yup.ref('new_password')], 'New passwords must match'),
            otherwise: s => s.strip()
        })
    }

    if (isRegister.value) {
        return yup.object({
            ...base,
            password_confirmation: yup.string()
                .required('Password confirmation is required')
                .oneOf([yup.ref('password')], 'Passwords must match')
        })
    }

    return yup.object({
        ...base,
        password_confirmation: yup.string().strip()
    })
})

const { handleSubmit, errors, values, setValues } = useForm({
    validationSchema: schema,
    initialValues: {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        new_password: '',
        new_password_confirmation: ''
    }
})

const { value: name } = useField('name')
const { value: email } = useField('email')
const { value: password } = useField('password')
const { value: password_confirmation } = useField('password_confirmation')
const { value: new_password } = useField('new_password')
const { value: new_password_confirmation } = useField('new_password_confirmation')

const errorMessage = ref('')

watch(() => store.user, newUser => {
    if (newUser) {
        setValues({
            name: newUser.name,
            email: newUser.email,
            password: '',
            password_confirmation: '',
            new_password: '',
            new_password_confirmation: ''
        })
    }
}, { immediate: true })

const submit = handleSubmit(async (values) => {
    errorMessage.value = ''

    try {
        const payload = JSON.parse(JSON.stringify(values))

        if (store.user) {
            await useUser.update(payload)
        } else {
            await useUser.register(payload)
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
                :class="['w-full p-2 border rounded', errors.name ? 'border-red-500' : '']" />
            <div v-if="errors.name" class="text-red-500 text-sm">{{ errors.name }}</div>
        </div>

        <div>
            <label class="block font-semibold">Email</label>
            <input v-model="email" type="email"
                :class="['w-full p-2 border rounded', errors.email ? 'border-red-500' : '']" />
            <div v-if="errors.email" class="text-red-500 text-sm">{{ errors.email }}</div>
        </div>

        <div>
            <label class="block font-semibold">{{ isRegister ? 'Password' : 'Current Password' }}</label>
            <input v-model="password" type="password"
                :class="['w-full p-2 border rounded', errors.password ? 'border-red-500' : '']" />
            <div v-if="errors.password" class="text-red-500 text-sm">{{ errors.password }}</div>
        </div>

        <div v-if="isRegister">
            <label class="block font-semibold">Confirm Password</label>
            <input v-model="password_confirmation" type="password"
                :class="['w-full p-2 border rounded', errors.password_confirmation ? 'border-red-500' : '']" />
            <div v-if="errors.password_confirmation" class="text-red-500 text-sm">
                {{ errors.password_confirmation }}
            </div>
        </div>

        <div v-else>
            <label class="block font-semibold">New Password</label>
            <input v-model="new_password" type="password"
                :class="['w-full p-2 border rounded', errors.new_password ? 'border-red-500' : '']" />
            <div v-if="errors.new_password" class="text-red-500 text-sm">
                {{ errors.new_password }}
            </div>

            <label class="block font-semibold">Confirm New Password</label>
            <input v-model="new_password_confirmation" type="password"
                :class="['w-full p-2 border rounded', errors.new_password_confirmation ? 'border-red-500' : '']" />
            <div v-if="errors.new_password_confirmation" class="text-red-500 text-sm">
                {{ errors.new_password_confirmation }}
            </div>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded">
            Save
        </button>

        <div v-if="errorMessage" class="text-red-500 text-sm mt-2">
            {{ errorMessage }}
        </div>
    </form>
</template>
