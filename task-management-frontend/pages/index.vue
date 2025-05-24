<template>
  <Tasks />
</template>

<script setup>
import Tasks from './tasks.vue'
definePageMeta({
  middleware: 'auth'
})
</script>