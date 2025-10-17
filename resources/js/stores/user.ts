import { defineStore } from 'pinia'
import { User } from '@/types/index'
import { useAuthStore } from './auth'
import axios from 'axios'

export const useUserStore = defineStore('user', {
  state: () => {
    return {

      id: 0,
      name: '',
      email: '',
      avatar: '',
      email_verified_at: null,
      created_at: '',
      updated_at: '',
      isAdmin: false,
      isTeam: false,
      bio: '',
      url: '',
      imageUrl: '',
      role: '',
      emailVerified: false
    } as User
  },
  actions: {
    set(user: User) {
      Object.assign(this, user)
    },
    async update(option: string, data: { oldPassword?: string, newPassword?: string, pic?: File } = {}) {
      let response;
      if (option === 'name' || option === 'role' || option === 'bio') {
        response = await axios.put('/api/user', {
          'name': this.name,
          'role': this.role,
          'bio': this.bio,
        })
      }

      if (option === 'password') {
        response = await axios.put('/api/user', {
          'oldpsw': data.oldPassword,
          'newpsw': data.newPassword,
        })
      }

      if (option === 'pic') {
        response = await axios.postForm('/api/user', {
          '_method': 'put',
          'pic': data.pic,
        });
      }

      if(response?.data.message === 'success'){
        const auth = useAuthStore();
        auth.refresh();
      }
    },

  }
})
