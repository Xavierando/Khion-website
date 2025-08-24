<template>

    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <AppLayout>
        <Transition appear>
            <section
                class="h-1/5 bg-[url(@images/homepage.jpg)]  bg-cover  flex flex-row item-center justify-center text-color:black relative">
            </section>
        </Transition>
        <Transition appear>
            <section class="lg:max-w-6xl p-5 mx-auto bg-white/20 rounded-lg text-black">
                <a :href="route('products.index')">
                    <h3 class="bg-blue-500 hover:bg-blue-300 text-white font-bold py-2 m-4 px-4 w-fit rounded">
                        Tutti i prodotti
                    </h3>
                </a>
            </section>
        </Transition>

        <CardSnowBig :product='product' scenario="show" :item="cartItem"></CardSnowBig>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Cart, Product } from '@/types';
import CardSnowBig from '@/components/ui/card/CardSnowBig.vue';
import { ref } from 'vue';


const props = defineProps<{
    product: Product,
    cart?: Cart,
}>()

const cartItem = props.cart?.items.find((v) => v.product.id === props.product.id);
if (props.cart) {
    cartItem
}

</script>
