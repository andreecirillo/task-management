import { useStore } from '~/stores/useStore'
import { useLoading } from '~/composables/useLoading'
import { useRuntimeConfig } from '#imports'

export const useUser = {
  get baseURL() {
    const config = useRuntimeConfig()
    return `${config.public.apiBase}/users`
  },

  async login(credentials: { email: string; password: string }) {
    const store = useStore()
    const { start, stop } = useLoading()
    start()

    try {
      const response = await fetch(`${this.baseURL}/login`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(credentials)
      })

      const result = await response.json()

      if (!response.ok) {
        throw { data: result }
      }

      if (result.access_token && result.user) {
        store.setUser({ name: result.user.name, email: result.user.email })
        store.setToken(result.access_token)
      }

      return result
    } finally {
      stop()
    }
  },

  async register(data: any) {
    const store = useStore()
    const { start, stop } = useLoading()
    start()

    try {
      const plainData = JSON.parse(JSON.stringify(data))

      const response = await fetch(`${this.baseURL}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(plainData)
      })

      const result = await response.json()

      if (!response.ok) {
        throw { data: result }
      }

      if (result.access_token && result.user) {
        store.setUser({ name: result.user.name, email: result.user.email })
        store.setToken(result.access_token)
      }

      return result
    } finally {
      stop()
    }
  },

  async update(data: any) {
    const store = useStore()
    const { start, stop } = useLoading()
    start()

    try {
      const token = store.token
      const plainData = JSON.parse(JSON.stringify(data))

      const response = await fetch(`${this.baseURL}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}`
        },
        body: JSON.stringify(plainData)
      })

      const result = await response.json()

      if (!response.ok) {
        throw { data: result }
      }

      if (result.user) {
        store.setUser({ name: result.user.name, email: result.user.email })
      }

      return result
    } finally {
      stop()
    }
  },

  async logout() {
    const store = useStore()
    const { start, stop } = useLoading()
    start()

    try {
      const token = store.token

      const response = await fetch(`${this.baseURL}/logout`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}`
        }
      })

      store.logout?.()

      return response
    } finally {
      stop()
    }
  }
}
