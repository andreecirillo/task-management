export const useApi = async (url: string, options: any = {}) => {

  let token = ''

  if (typeof window !== 'undefined') {
    token = localStorage.getItem('token') || ''
  }

  const headers = {
    ...options.headers,
    Authorization: `Bearer ${token}`
  }

  const config = useRuntimeConfig()

  return await $fetch(`${config.public.apiBase}${url}`, {
    ...options,
    headers
  })
}
