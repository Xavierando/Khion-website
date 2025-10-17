<script setup lang="ts">
import { useCartStore } from '@/stores/cart';
import { useProductsStore } from '@/stores/products';
import { computed } from 'vue';

const props = defineProps<{
    IdProdotto: number;
}>();

const cart = useCartStore();
const products = useProductsStore();
const prodotto = computed(() => products.findProduct(props.IdProdotto));
const cartItem = computed(() => cart.find(props.IdProdotto));




const handleAddQuantity = () => {
    console.log(prodotto.value)
    if (prodotto.value) {
        if(prodotto.value.quantity > 0){
            cart.increment(prodotto.value);
        }
    }
}
const handleSubQuantity = () => {
    if (prodotto.value && cartItem.value) {
        if(cartItem.value.quantity ?? 0 > 0){
            cart.decrement(prodotto.value);
        }
    }
}

</script>

<template>

    <v-sheet>
        <v-container class="d-flex align-center">

            <v-btn class="ma-2" color="secondary" icon="mdi-minus" @click="handleSubQuantity">

            </v-btn>
            <v-container>
                {{ cartItem?.quantity ?? 0 }}
            </v-container>
            <v-btn class="ma-2" color="secondary" icon="mdi-plus" @click="handleAddQuantity">

            </v-btn>
        </v-container>
    </v-sheet>
</template>