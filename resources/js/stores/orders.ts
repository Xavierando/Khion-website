import { defineStore } from 'pinia'
import axios from 'axios'
import { CartItem, Order } from '@/types'
import { useProductsStore } from './products'
import dayjs from 'dayjs'



export const useOrdersStore = defineStore('orders', {
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
    find: (state) => (orderId: number) => state.list.find((order) => order.id === orderId) ?? false
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
  checkoutUrl(): Promise<string | false>;
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
  async checkoutUrl(): Promise<string | false> {
    const response = await axios.get('/api/checkout/' + this.id.toString());
    if (response.request.status === 200) {
      if (response.data.message === 'success') {
        return response.data.data.url
      }
    }
    return false
  };
  static sortByStatusAndDate(OrdineA: Ordine, OrdineB: Ordine): number {
    const statusOrder = [
      'pending',
      'paid',
      'production',
      'expedited',
    ]

    if ((statusOrder.indexOf(OrdineA.status) - statusOrder.indexOf(OrdineB.status)) !== 0) {
      return statusOrder.indexOf(OrdineA.status) - statusOrder.indexOf(OrdineB.status);
    }

    return OrdineB.id - OrdineA.id
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
    const products = useOrdersStore();
    products.delete(this.id);
  }
  getItemsList() {
    return this.items.map((item) => {
      const products = useProductsStore();
      return {
        id: item.id,
        quantity: item.quantity,
        product: products.findProduct(item.product)
      }
    })
  }
}