<script setup lang="ts">
import { useStore } from '~/stores/useStore'
import { useLoading } from '~/composables/useLoading'

const store = useStore()
const { isLoading } = useLoading()

onMounted(() => {
  store.hydrate()
})
</script>

<template>
  <div>
    <NuxtRouteAnnouncer />
    <Header></Header>

    <ClientOnly>
      <div v-if="isLoading" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="animate-spin rounded-full h-12 w-12 border-t-4 border-b-4 border-white"></div>
      </div>
    </ClientOnly>


    <NuxtPage />
  </div>
</template>
