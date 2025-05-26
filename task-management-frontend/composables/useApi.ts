import { useLoading } from '~/composables/useLoading'

export const useApi = async <T>(url: string, options: any = {}): Promise<T> => {
  const { start, stop } = useLoading()
  start()

  let token = ''

  if (typeof window !== 'undefined') {
    token = localStorage.getItem('token') || ''
  }

  const headers = {
    ...options.headers,
    Authorization: `Bearer ${token}`
  }

  const config = useRuntimeConfig()

  try {
    return await $fetch<T>(`${config.public.apiBase}${url}`, {
      ...options,
      headers
    })
  } finally {
    stop()
  }
}
