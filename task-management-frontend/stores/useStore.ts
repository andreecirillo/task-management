import { defineStore } from 'pinia'

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
            if (typeof window !== 'undefined') {
                const user = localStorage.getItem('user')
                const token = localStorage.getItem('token')
                const categories = localStorage.getItem('categories')

                this.user = user ? (JSON.parse(user) as User) : null
                this.token = token || ''
                this.categories = categories ? (JSON.parse(categories) as Category[]) : []
            }
        },

        setUser(newUser: User) {
            this.user = newUser
            if (typeof window !== 'undefined') {
                localStorage.setItem('user', JSON.stringify(newUser))
            }
        },

        setToken(newToken: string) {
            this.token = newToken
            if (typeof window !== 'undefined') {
                localStorage.setItem('token', newToken)
            }
        },

        setCategories(newCategories: Category[]) {
            this.categories = newCategories
            if (typeof window !== 'undefined') {
                localStorage.setItem('categories', JSON.stringify(newCategories))
            }
        },

        logout() {
            this.user = null
            this.token = ''
            this.categories = []
            if (typeof window !== 'undefined') {
                localStorage.removeItem('user')
                localStorage.removeItem('token')
                localStorage.removeItem('categories')
            }
        }
    }
})
