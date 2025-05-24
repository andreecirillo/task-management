<script setup lang="ts">
definePageMeta({
  middleware: 'auth'
})

import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useTask } from '~/composables/useTask'
import { useStore } from '~/stores/useStore'

const store = useStore()

const router = useRouter()

const tasks = ref([])
const categories = ref([])

const search = ref('')
const categoryFilter = ref('')

const loadTasks = async () => {
  try {
    const data = await useTask.fetchAll()
    tasks.value = data
    categories.value = store.categories
  } catch (error: any) {
    console.error('Tasks error:', error.message)
  }
}

onMounted(() => {
  loadTasks()
})

const filteredTasks = computed(() => {
  return tasks.value.filter((task: any) => {
    const matchesCategory = categoryFilter.value
      ? task.category_id === categoryFilter.value
      : true
    const matchesSearch =
      task.title.toLowerCase().includes(search.value.toLowerCase()) ||
      task.description.toLowerCase().includes(search.value.toLowerCase())

    return matchesCategory && matchesSearch
  })
})

const addTask = async () => {
  router.push('/task')
}

const editTask = async (task: any) => {
  router.push('/task?id=' + task.id)
}

const deleteTask = async (id: number) => {
  try {
    await useTask.remove(id)
    await loadTasks()
  } catch (error: any) {
    console.error('Task error:', error.message)
  }
}

function getCategoryName(category_id: number | null) {
  if (!category_id) return ''

  const category = categories.value.find(cat => cat.id === category_id)

  return category ? category.name : ''
}


</script>

<template>
  <div class="p-4 max-w-4xl mx-auto">

    <h2 class="text-2xl font-bold mb-6">Tasks</h2>

    <!-- Filtros -->
    <div class="flex flex-col md:flex-row gap-4 mb-6">
      <input v-model="search" type="text" placeholder="Search for title or description"
        class="w-full md:w-1/2 p-2 border rounded" />

      <select v-model="categoryFilter" class="w-full md:w-1/4 p-2 border rounded">
        <option value="">Category</option>
        <option v-for="cat in categories" :key="cat.id" :value="cat.id">
          {{ cat.name }}
        </option>
      </select>

      <button @click="addTask" class="w-full md:w-auto bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
        New Task
      </button>
    </div>

    <!-- Lista de Tarefas -->
    <ul class="space-y-4">
      <li v-for="task in filteredTasks" :key="task.id"
        class="flex flex-col md:flex-row md:items-center justify-between bg-white p-4 rounded shadow">
        <div>
          <h2 class="text-lg font-semibold">{{ task.title }}</h2>
          <p class="text-gray-600">{{ task.description }}</p>
          <span class="text-xs text-gray-500">Category: {{ getCategoryName(task.category_id) }}</span>
        </div>
        <div class="flex gap-2 mt-2 md:mt-0">
          <button @click="editTask(task)" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
            Editar
          </button>
          <button @click="deleteTask(task.id)" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
            Excluir
          </button>
        </div>
      </li>
    </ul>
  </div>
</template>
