import { useStore } from '~/stores/useStore'

export const useUser = {
  baseURL: 'http://127.0.0.1:8000/api/users',

  async login(credentials: { email: string; password: string }) {
    const store = useStore()

    const response = await fetch(`${this.baseURL}/login`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(credentials)
    })

    const result = await response.json()

    if (!response.ok) {
      throw { data: result }
    }

    if (result.access_token) {
      store.setUser({ name: result.user.name, email: result.user.email })
      store.setToken(result.access_token)
    }

    return result
  },

  async register(data: { name: string; email: string; password: string }) {
    const store = useStore()

    const response = await fetch(`${this.baseURL}`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    })

    const result = await response.json()

    if (!response.ok) {
      throw { data: result }
    }

    if (result.access_token) {
      store.setUser({ name: result.user.name, email: result.user.email })
      store.setToken(result.access_token)
    }

    return result
  },

  async update(data: { name: string; email: string; password: string }) {
    const store = useStore()
    const token = store.token

    const response = await fetch(`${this.baseURL}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`
      },
      body: JSON.stringify(data)
    })

    const result = await response.json()

    store.setUser({ name: result.user.name, email: result.user.email })

    if (!response.ok) {
      throw { data: result }
    }

    return result
  },

  async logout() {
    const store = useStore()
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
  }
}
