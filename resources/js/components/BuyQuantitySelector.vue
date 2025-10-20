<script setup lang="ts">
import { useAuthStore } from '@/stores/auth';
import { useCartStore } from '@/stores/cart';
import { useProductsStore } from '@/stores/products';
import { computed,ref } from 'vue';

const props = defineProps<{
    IdProdotto: number;
}>();

const cart = useCartStore();
const products = useProductsStore();
const prodotto = computed(() => products.findProduct(props.IdProdotto));
const cartItem = computed(() => cart.find(props.IdProdotto));
const loginRequired = ref(false);
const auth = useAuthStore();




const handleAddQuantity = () => {
    if(!auth.loggedIn){
        loginRequired.value = true;
        return 0;
    }
    if (prodotto.value) {
        if (prodotto.value.quantity > 0) {
            cart.increment(prodotto.value);
        }
    }
}
const handleSubQuantity = () => {
    if(!auth.loggedIn){
        loginRequired.value = true;
        return 0;
    }
    if (prodotto.value && cartItem.value) {
        if (cartItem.value.quantity ?? 0 > 0) {
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
    <v-dialog max-width="340" v-model="loginRequired">
            <v-card prepend-icon="mdi-package"
                text="Il login é richiesto per procedere con gli acquisti"
                title="Login richiesto">
                <template v-slot:actions>
                    <v-btn class="ml-auto" text="Login" @click="$router.push('/login')"></v-btn>
                </template>
            </v-card>
    </v-dialog>
</template>