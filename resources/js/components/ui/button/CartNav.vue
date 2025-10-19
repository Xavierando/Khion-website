<script setup lang="ts">
import { useCartStore } from '@/stores/cart';
import { useProductsStore } from '@/stores/products';
import { computed, ref } from 'vue';
import { useRouter } from 'vue-router';
const cart = useCartStore();
const products = useProductsStore();
const router = useRouter();

const cartItems = computed(() => cart.dialogList)

const navigateToProduct = (productId: number) => {
    console.log(productId);
    router.replace({ path: '/prodotti/' + productId.toString() });
}

const handleCheckout = async () => {
    const url = await cart.transformToOrder();
    window.location.href = url;
}

</script>

<template>
    <v-btn class="ms-1" color="medium-emphasis" icon="mdi-cart-outline">
        <v-badge v-if="cart.countTotalQuantity > 0" location="top right" color="primary"
            :content="cart.countTotalQuantity">
            <v-icon>mdi-cart-outline</v-icon>
            <v-menu activator="parent" origin="top">
                <v-list density="compact" class="pa-0" rounded="0">
                    <v-card class="elevation-0" rounded="0" append-icon="mdi-arrow-right" title="Checkout"
                        color="primary" @click="handleCheckout">
                    </v-card></v-list>
                <v-list density="compact" class="pa-0" rounded="0" v-for="item in cartItems" :key="item.id">
                    <v-card @click="navigateToProduct(item.product.id)" v-if="(typeof item.product !== 'boolean')"
                        class="elevation-0">
                        <v-card-title>{{ item.product.name }}</v-card-title>
                        <v-card-subtitle>quantita: {{ item.quantity }}</v-card-subtitle>
                    </v-card>
                </v-list>
            </v-menu>
        </v-badge>
        <v-icon v-if="cart.countTotalQuantity === 0">mdi-cart-outline</v-icon>
    </v-btn>
</template>