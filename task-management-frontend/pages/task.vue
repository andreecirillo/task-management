<script setup lang="ts">
definePageMeta({
    middleware: 'auth'
})

import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useTask } from '~/composables/useTask'
import { useCategory } from '~/composables/useCategory'
import { useStore } from '~/stores/useStore'

const store = useStore()

const route = useRoute()
const router = useRouter()

const form = ref({
    title: '',
    description: '',
    category: ''
})

const categories = ref([])

const taskId = route.query.id

onMounted(async () => {
    categories.value = store.categories
    if (taskId) {
        const task = await useTask.fetchById(taskId)
        form.value = {
            title: task.title,
            description: task.description,
            category: task.category
        }
    }
})

const errorMessage = ref('')

const submit = async () => {
    errorMessage.value = ''

    if (taskId) {
        await useTask.update(taskId, form.value)
        router.push('/tasks')
    } else {
        try {
            await useTask.create(form.value)
            router.push('/tasks')
        } catch (error: any) {
            if (error.data) {
                errorMessage.value = Object.values(error.data).flat().join(' ')
            } else {
                errorMessage.value = error.message
            }
        }
    }
}

</script>

<template>

    <form @submit.prevent="submit" class="max-w-md mx-auto p-4 space-y-4">

        <h2 class="text-2xl font-bold mb-6">Tasks</h2>

        <div>
            <label class="block font-semibold">Title</label>
            <input v-model="form.title" type="text" class="w-full p-2 border rounded" />
        </div>

        <div>
            <label class="block font-semibold">Description</label>
            <input v-model="form.description" type="text" class="w-full p-2 border rounded" />
        </div>

        <div>
            <label class="block font-semibold">Category</label>
            <select v-model="form.category" class="w-full p-2 border rounded">
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
