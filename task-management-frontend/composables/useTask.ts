import { useApi } from '~/composables/useApi'

export const useTask = {
  async fetchAll () { return await useApi('/tasks') },
  async fetchById (id: string) {  return await useApi(`/tasks/${id}`) },
  async create (data: Record<string, any>) { return await useApi('/tasks', { method: 'POST', body: data }) },
  async update (id: string, data: Record<string, any>) { return await useApi(`/tasks/${id}`, { method: 'PUT', body: data }) },
  async remove (id: string) { return await useApi(`/tasks/${id}`, { method: 'DELETE' }) },
}
