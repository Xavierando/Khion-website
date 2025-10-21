import { defineStore } from "pinia";
import axios from "axios";
import { Cart } from "@/types";
import { useProductsStore } from "./products";
import { Prodotto } from "./products";

export const useCartStore = defineStore("cart", {
    state: () => {
        return {
            items: [],
        } as Cart;
    },
    actions: {
        async fetchCart() {
            try {
                const response = await axios.get("/api/cart");
                if (response.request.status === 200) {
                    if (response.data.message === "success") {
                        this.items = response.data.data.cart.items;
                        const products = useProductsStore();
                        products.fetchAllProducts();
                    }
                }
            } catch (error) {
                // let the form component display the error
                return error;
            }
        },
        async UpdateItem(product: Prodotto, quantity: number) {
            try {
                const response = await axios.patch("/api/cart/", {
                    product: product.id,
                    quantity,
                });
                if (response.request.status === 200) {
                    if (response.data.message === "success") {
                        this.items = response.data.data.cart.items;
                        const products = useProductsStore();
                        products.fetchProduct(product.id);
                    }
                }
            } catch (error) {
                // let the form component display the error
                return error;
            }
        },
        async increment(product: Prodotto) {
            const quantity = this.find(product.id)?.quantity ?? 0;
            await this.UpdateItem(product, quantity + 1);
        },
        async decrement(product: Prodotto) {
            const quantity = this.find(product.id)?.quantity ?? 0;
            await this.UpdateItem(product, quantity > 0 ? quantity - 1 : 0);
        },
        async transformToOrder() {
            try {
                const response = await axios.post("/api/checkout");
                if (response.request.status === 200) {
                    if (response.data.message === "success") {
                        console.log(response.data.data.url);
                        return response.data.data.url;
                    }
                }
            } catch (error) {
                // let the form component display the error
                return error;
            }
        },
    },
    getters: {
        all: (state) =>
            state.items
                .filter((item) => item.quantity > 0)
                .sort((itemA, itemB) => itemA.id - itemB.id),
        find: (state) => (productId: number) =>
            state.items.find((item) => item.product === productId),
        countTotalQuantity: (state) =>
            state.items.reduce(
                (sum: number, item) => (sum += item.quantity),
                0,
            ),
        countTotalPrice: (state) => {
            const products = useProductsStore();
            return state.items.reduce((sum: number, item) => {
                const product = products.findProduct(item.product);
                return product
                    ? (sum += item.quantity * product.base_price)
                    : sum;
            }, 0);
        },
        dialogList: (state) =>
            state.items
                .filter((item) => item.quantity > 0)
                .sort((itemA, itemB) => itemA.id - itemB.id)
                .map((item) => {
                    const products = useProductsStore();
                    return {
                        id: item.id,
                        quantity: item.quantity,
                        product: products.findProduct(item.product),
                    };
                }),
    },
});
