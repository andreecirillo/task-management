import { ref } from 'vue'

const isLoading = ref(false)

export function useLoading() {
    function start() {
        isLoading.value = true
    }

    function stop(delay = 300) {  // Delay padrão de 300ms
        setTimeout(() => {
            isLoading.value = false
        }, delay)
    }

    return {
        isLoading,
        start,
        stop
    }
}
