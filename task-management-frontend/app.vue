<script setup lang="ts">
import { useStore } from '~/stores/useStore'

const store = useStore()

onMounted(() => {
  store.hydrate()
})
</script>

<template>
  <div>
    <NuxtRouteAnnouncer />
    <Header></Header>
    <NuxtPage />
  </div>
</template>