<script setup lang="ts">
definePageMeta({
    middleware: 'auth'
})

import { useRoute, useRouter } from 'vue-router'
import { useTask } from '~/composables/useTask'
import { useStore } from '~/stores/useStore'
import { useForm, useField } from 'vee-validate'
import * as yup from 'yup'
import { ref, onMounted } from 'vue'

const store = useStore()
const route = useRoute()
const router = useRouter()

const categories = ref([])

const taskId = route.query.id

onMounted(async () => {
    categories.value = store.categories
    if (taskId) {
        const task = await useTask.fetchById(taskId)
        setValues({
            title: task.title,
            description: task.description,
            category_id: task.category_id
        })
    }
})

const schema = yup.object({
    title: yup.string().required('Title is required'),
    description: yup.string(),
    category_id: yup.string().nullable()
})

const { handleSubmit, errors, setValues } = useForm({
    validationSchema: schema,
    initialValues: {
        title: '',
        description: '',
        category_id: ''
    }
})

const { value: title, meta: metaTitle } = useField('title')
const { value: description, meta: metaDescription } = useField('description')
const { value: category_id, meta: metaCategoryId } = useField('category_id')

const errorMessage = ref('')

const submit = handleSubmit(async (values) => {
    errorMessage.value = ''

    try {
        if (taskId) {
            await useTask.update(taskId, values)
        } else {
            await useTask.create(values)
        }
        router.push('/tasks')
    } catch (error: any) {
        if (error.data) {
            try {
                errorMessage.value = Object.values(error.data).flat().join(' ')
            } catch {
                errorMessage.value = JSON.stringify(error.data)
            }
        } else {
            errorMessage.value = error.message
        }
    }
})
</script>

<template>
    <form @submit.prevent="submit" class="max-w-md mx-auto p-4 space-y-4">
        <h2 class="text-2xl font-bold mb-6">Tasks</h2>

        <div>
            <label class="block font-semibold">Title</label>
            <input v-model="title" type="text"
                :class="['w-full p-2 border rounded', errors.title && metaTitle.touched ? 'border-red-500' : '']" />
            <div v-if="errors.title && metaTitle.touched" class="text-red-500 text-sm">{{ errors.title }}</div>
        </div>

        <div>
            <label class="block font-semibold">Description</label>
            <input v-model="description" type="text"
                :class="['w-full p-2 border rounded', errors.description && metaDescription.touched ? 'border-red-500' : '']" />
            <div v-if="errors.description && metaDescription.touched" class="text-red-500 text-sm">
                {{ errors.description }}
            </div>
        </div>

        <div>
            <label class="block font-semibold">Category</label>
            <select v-model="category_id" class="w-full p-2 border rounded">
                <option disabled value="">Select</option>
                <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                    {{ cat.name }}
                </option>
            </select>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded">
            Save
        </button>

        <div v-if="errorMessage" class="text-red-500 text-sm mt-2">
            {{ errorMessage }}
        </div>
    </form>
</template>
