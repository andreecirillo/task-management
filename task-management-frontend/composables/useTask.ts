import { useApi } from '~/composables/useApi'

export interface Task {
  id: number
  title: string
  description?: string
  category_id?: number
}

export const useTask = {
  async fetchAll(): Promise<Task[]> {
    return await useApi<Task[]>('/tasks')
  },

  async fetchById(id: string): Promise<Task> {
    return await useApi<Task>(`/tasks/${id}`)
  },

  async create(data: Record<string, any>): Promise<Task> {
    return await useApi<Task>('/tasks', { method: 'POST', body: data })
  },

  async update(id: string, data: Record<string, any>): Promise<Task> {
    return await useApi<Task>(`/tasks/${id}`, { method: 'PUT', body: data })
  },

  async remove(id: string): Promise<{ message: string }> {
    return await useApi<{ message: string }>(`/tasks/${id}`, { method: 'DELETE' })
  }
}
