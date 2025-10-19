import { defineStore } from 'pinia'
import axios from 'axios'
import { CartItem, Image, Order, Product } from '@/types'
import { useSearchStore } from './search'
import { Prodotto } from './products'



export const useProductsStore = defineStore('products', {
  state: () => {
    return {
      list: [],
    } as Orders
  },
  actions: {
    async fetchAll() {
      try {
        const response = await axios.get('/api/orders')
        if (response.request.status === 200) {
          if (response.data.message === 'success') {
            this.list = [];
            for (const key in response.data.data.orders) {
              this.list.push(new Ordine(response.data.data.orders[key] as Order))
            }
          }
        }
      } catch (error) {
        // let the form component display the error
        return error
      }
    },
    async fetch(id: string | number) {
      try {
        id = Number(id);
        const response = await axios.get('/api/orders/' + id.toString(),)
        if (response.request.status === 200) {
          if (response.data.message === 'success') {
            this.list = this.list.filter((order) => order.id !== id)
            this.list.push(new Ordine(response.data.data.order as Order))
          }
        }
      } catch (error) {
        // let the form component display the error
        return error
      }
    },
    delete(id: number) {
      this.list = this.list.filter((order) => order.id !== id)
    }
  },
  getters: {
    all: (state) =>
      state.list
        .sort(Ordine.sortByStatusAndDate),
    findOrder: (state) => (orderId: number) => state.list.find((order) => order.id === orderId) ?? false
    ,
  },
})

interface Orders {
  list: Ordine[],
}


export interface Ordine {
  id: number;
  status: string;
  statusOptions: string;
  total: number;
  items: CartItem[];
  setStatus(newStatus: string): Promise<boolean>;
  sortByStatusAndDate(OrdineA: Ordine, OrdineB: Ordine): number;
  delete(): void
  deleteFromServer(): Promise<boolean>
  deleteFromLocalStore(): void
}

export class Ordine implements Ordine {
  constructor(data: Order) {
    this.id = data.id;
    this.status = data.status;
    this.statusOptions = data.statusOptions;
    this.total = data.total;
    this.items = data.items;
  }
  async setStatus(newStatus: string): Promise<boolean> {

    const response = await axios.put('/api/order/' + this.id.toString(),
      {
        status: newStatus,
      });
    if (response.request.status === 200) {
      if (response.data.message === 'success') {
        this.status = newStatus
        return true
      }
    }

    return false
  };
  static sortByStatusAndDate(OrdineA: Ordine, OrdineB: Ordine): number {
    return 0
  };
  async delete() {
    this.deleteFromServer().then((isSuccess) => isSuccess && this.deleteFromLocalStore())

  };
  async deleteFromServer(): Promise<boolean> {
    try {
      const response = await axios.delete('/api/orders/' + this.id.toString())
      if (response.request.status === 200) {
        if (response.data.message === 'success') {
          return true
        }
      }
      return false
    } catch (error) {
      return false
    }
  }
  deleteFromLocalStore() {
    const products = useProductsStore();
    products.delete(this.id);
  }
}


export interface ImmagineProdotto {
  id: number | string;
  productId: number;
  thumbnail: string;
  src: string;
  caption: string;
  blob?: File;
  deletable?: boolean;
  sync(): Promise<boolean>;
  create(): Promise<boolean>;
  setAsDefault(): Promise<boolean>;
  delete(): Promise<boolean>;
}

export class ImmagineProdotto implements ImmagineProdotto {
  constructor(data: Image) {
    this.id = data.id;
    this.thumbnail = data.thumbnail;
    this.src = data.src;
    this.caption = data.caption;
    this.blob = undefined;
    this.deletable = false;
  };
  async sync(): Promise<boolean> {
    if (this.deletable) {
      return this.delete();
    }
    if (this.id === 'new') {
      return this.create();
    }
    return false;
  }
  async setAsDefault(): Promise<boolean> {
    const response = await axios.put('/api/gallery/' + this.id.toString());
    if (response.request.status === 200) {
      if (response.data.message === 'success') {
        return true

      }
    }
    return true
  };
  async delete(): Promise<boolean> {
    const response = await axios.delete('/api/gallery/' + this.id.toString());
    if (response.request.status === 200) {
      if (response.data.message === 'success') {
        return true
      }
    }
    return true
  };
  async create(): Promise<boolean> {
    if (this.blob) {
      const response = await axios.postForm('/api/products/' + this.productId.toString() + '/gallery', {
        file: this.blob
      });
      if (response.request.status === 200) {
        if (response.data.message === 'success') {
          this.id = Number(response.data.data.id);
          this.blob = undefined;
          return true
        }
      }
    }
    return false
  };
}