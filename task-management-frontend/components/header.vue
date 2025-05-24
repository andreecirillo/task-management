<script setup lang="ts">
import { useRouter } from 'vue-router'
import { computed } from 'vue'
import { useUser } from '~/composables/useUser'
import { useStore } from '~/stores/useStore'

const router = useRouter()
const store = useStore()

const user = computed(() => store.user)
const isLogged = computed(() => !!store.user)

const logout = async () => {
    await useUser.logout()
    router.push('/login')
}
</script>

<template>
    <header class="flex items-center justify-between p-4 bg-gray-800 text-white">
        <div v-if="isLogged" class="text-lg">
            Welcome, {{ user?.name }}
        </div>

        <h1 class="text-xl font-bold text-center flex-1">
            Tasks Management
        </h1>

        <nav v-if="isLogged" class="space-x-4">
            <a href="/tasks" class="hover:underline">Tasks</a>
            <a href="/user" class="hover:underline">Edit User</a>
            <button @click="logout" class="hover:underline">Logout</button>
        </nav>
        <nav v-else>
            <a href="/user" class="hover:underline">Register</a>
        </nav>
    </header>
</template>
