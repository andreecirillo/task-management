import { defineStore } from 'pinia'
import { useLoading } from '~/composables/useLoading'

interface User {
    name: string
    email: string
}

interface Category {
    id: number
    name: string
}

export const useStore = defineStore('store', {
    state: () => ({
        user: null as User | null,
        token: '',
        categories: [] as Category[],
    }),

    actions: {
        hydrate() {
            const { start, stop } = useLoading()
            start()

            try {
                if (typeof window !== 'undefined') {
                    const user = localStorage.getItem('user')
                    const token = localStorage.getItem('token')
                    const categories = localStorage.getItem('categories')

                    this.user = user ? (JSON.parse(user) as User) : null
                    this.token = token || ''
                    this.categories = categories ? (JSON.parse(categories) as Category[]) : []
                }
            } finally {
                stop()
            }
        },

        setUser(newUser: User) {
            const { start, stop } = useLoading()
            start()

            try {
                this.user = newUser
                if (typeof window !== 'undefined') {
                    localStorage.setItem('user', JSON.stringify(newUser))
                }
            } finally {
                stop()
            }
        },

        setToken(newToken: string) {
            const { start, stop } = useLoading()
            start()

            try {
                this.token = newToken
                if (typeof window !== 'undefined') {
                    localStorage.setItem('token', newToken)
                }
            } finally {
                stop()
            }
        },

        setCategories(newCategories: Category[]) {
            const { start, stop } = useLoading()
            start()

            try {
                this.categories = newCategories
                if (typeof window !== 'undefined') {
                    localStorage.setItem('categories', JSON.stringify(newCategories))
                }
            } finally {
                stop()
            }
        },

        logout() {
            const { start, stop } = useLoading()
            start()

            try {
                this.user = null
                this.token = ''
                this.categories = []
                if (typeof window !== 'undefined') {
                    localStorage.removeItem('user')
                    localStorage.removeItem('token')
                    localStorage.removeItem('categories')
                }
            } finally {
                stop()
            }
        }
    }
})
