import { useApi } from '~/composables/useApi'

export interface Category {
  id: number
  name: string
}

export const useCategory = {
  async fetchAll(): Promise<Category[]> {
    return await useApi<Category[]>('/categories')
  }
}
