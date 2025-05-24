import { useApi } from '~/composables/useApi'

export const useCategory =  {
  async fetchAll () { return await useApi('/categories') },
}
