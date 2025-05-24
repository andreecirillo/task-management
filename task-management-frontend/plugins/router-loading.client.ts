import { defineNuxtPlugin } from '#app'
import { useLoading } from '~/composables/useLoading'
import { useRouter } from 'vue-router'

export default defineNuxtPlugin(() => {
    const loading = useLoading()
    const router = useRouter()

    router.beforeEach(() => {
        loading.start()
    })

    router.afterEach(() => {
        loading.stop()
    })
})
