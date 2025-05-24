<script setup lang="ts">
import { useRouter } from 'vue-router'
import { useUser } from '~/composables/useUser'
import { useCategory } from '~/composables/useCategory'
import { useStore } from '~/stores/useStore'
import { useForm, useField } from 'vee-validate'
import * as yup from 'yup'

const store = useStore()
const router = useRouter()

const schema = yup.object({
  email: yup.string().email('Invalid email').required('Email is required'),
  password: yup.string().required('Password is required')
})

const { handleSubmit, errors } = useForm({
  validationSchema: schema
})

const { value: email } = useField('email')
const { value: password } = useField('password')

const errorMessage = ref('')

const submit = handleSubmit(async (values) => {
  errorMessage.value = ''
  try {
    await useUser.login(values)
    const categories = await useCategory.fetchAll()
    store.setCategories(categories)
    router.push('/tasks')
  } catch (error: any) {
    errorMessage.value = error.message
  }
})
</script>

<template>
  <div class="flex min-h-screen items-center justify-center bg-gray-100">
    <form @submit.prevent="submit" class="w-full max-w-sm bg-white p-8 rounded shadow">
      <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>

      <input v-model="email" type="email" placeholder="Email" class="w-full mb-2 p-3 border rounded"
        :class="{ 'border-red-500': errors.email }" />
      <div v-if="errors.email" class="text-red-500 text-sm mb-2">
        {{ errors.email }}
      </div>

      <input v-model="password" type="password" placeholder="Password" class="w-full mb-2 p-3 border rounded"
        :class="{ 'border-red-500': errors.password }" />
      <div v-if="errors.password" class="text-red-500 text-sm mb-2">
        {{ errors.password }}
      </div>

      <button type="submit" class="w-full bg-blue-500 text-white p-3 rounded hover:bg-blue-600">
        Submit
      </button>

      <div v-if="errorMessage" class="text-red-500 text-sm mt-2">
        {{ errorMessage }}
      </div>
    </form>
  </div>
</template>
