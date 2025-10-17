import { defineStore } from 'pinia'
import axios from 'axios'
import { Image, Product, Tag } from '@/types'
import dayjs from 'dayjs'
import { useSearchStore } from './search'



export const useProductsStore = defineStore('products', {
  state: () => {
    return {
      list: [],
    } as Products
  },
  actions: {
    async fetchAllProducts() {
      try {
        const response = await axios.get('/api/products')
        if (response.request.status === 200) {
          if (response.data.message === 'success') {
            this.list = [];
            for (const key in response.data.data.products) {
              console.log(response.data.data.products[key])
              this.list.push(new Prodotto(response.data.data.products[key] as Product))
            }
          }
        }
      } catch (error) {
        // let the form component display the error
        return error
      }
    },
    async fetchProduct(id: string | number) {
      try {
        id = Number(id);
        const response = await axios.get('/api/products/' + id.toString(),)
        if (response.request.status === 200) {
          if (response.data.message === 'success') {
            this.list = this.list.filter((prodotto) => prodotto.id !== id)
            this.list.push(new Prodotto(response.data.data.product as unknown as Product))
          }
        }
      } catch (error) {
        // let the form component display the error
        return error
      }
    },
    async createProduct() {
      try {
        const response = await axios.post('/api/products/', {})
        if (response.request.status === 200) {
          if (response.data.message === 'success') {
            this.list.push(new Prodotto(response.data.data.product as unknown as Product))
            return Number(response.data.data.product.id);
          }
        }
        return false
      } catch (error) {
        // let the form component display the error
        return false
      }
    }
  },
  getters: {
    hightlight: (state) =>
      state.list
        .filter((product) => product.quantity > 0)
        .sort((productA, productB) => dayjs(productA.created).unix() - dayjs(productB.created).unix())
        .slice(0, 4),
    all: (state) =>
      state.list
        .sort((productA, productB) => productA.id - productB.id),
    filtered: (state) => {
      const search = useSearchStore();

      if (search.tearm.length === 0) {
        return state.list
          .sort((productA, productB) => dayjs(productA.created).unix() - dayjs(productB.created).unix())

      }
      return state.list
        .filter((product) => product.name.includes(search.tearm))
        .sort((productA, productB) => dayjs(productA.created).unix() - dayjs(productB.created).unix())
    },
    findProduct: (state) => (productId: number) => state.list.find((product) => product.id === productId) ?? false
    ,
  },
})

interface Products {
  list: Prodotto[],
}

export interface Prodotto {
  id: number;
  name: string;
  code: string;
  description: string;
  base_price: number;
  quantity: number;
  base_quantity: number;
  images: Image[];
  default_images: Image;
  tags: Tag[];
  created: string;
  addTag(Tag: Tag): void;
  removeTag(Tag: Tag): void;
  addImage(File: File): void;
  removeImage(src: string): void;
  setImageAsDefault(src: string): void;
  update(): Promise<boolean>;
}

export class Prodotto implements Prodotto {
  constructor(data: Product) {
    this.id = data.id;
    this.name = data.name;
    this.code = data.code;
    this.description = data.description;
    this.base_price = data.base_price;
    this.quantity = data.quantity;
    this.base_quantity = data.base_quantity;
    this.images = data.images;
    if (data.default_images)
      this.default_images = data.default_images;
    this.tags = data.tags;
    this.created = data.created;
  }
  addTag(Tag: Tag): void {
    if (!this.tags.some((tag: Tag) => tag.id === Tag.id)) {
      this.tags.push(Tag);
    }
  }
  removeTag(Tag: Tag): void {
    this.tags = this.tags.filter((tag: Tag) => tag.id !== Tag.id)
  };
  addImage(File: File): void {
    const newImage: Image = {
      thumbnail: URL.createObjectURL(File),
      src: URL.createObjectURL(File),
      caption: "",
      blob: File
    }
    this.images.push(newImage)
  };
  removeImage(src: string): void {
    this.images = this.images.filter((image: Image) => image.src !== src)
  };
  setImageAsDefault(src: string): void {
    for (const imageIndex in this.images) {
      if (this.images[imageIndex].src === src) {
        this.default_images = this.images[Number(imageIndex)]
        console.log('ciao')
      }
    }
  };
  async update(): Promise<boolean> {

    const response = await axios.postForm('/api/products/' + this.id.toString(),
      {
        '_method': 'put',
        code: this.code,
        name: this.name,
        description: this.description,
        base_price: this.base_price,
        quantity: this.base_quantity,
        tags: this.tags,
        images: this.images,
        default_images: this.default_images,
      });
    if (response.request.status === 200) {
      if (response.data.message === 'success') {
        return true

      }
    }

    return false
  };
}