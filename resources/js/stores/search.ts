import { defineStore } from 'pinia'

export const useSearchStore = defineStore('search', {
  state: () => {
    return  {tearm:''} as {tearm:string}
  },
  actions: {
    update(tearm : string) {
      this.tearm = tearm;
    }
  }
})
