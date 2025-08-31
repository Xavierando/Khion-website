<template>

    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <AppLayout>
        <Transition appear>
            <section
                class="h-16 sm:h-1/5 sm:bg-[url(@images/homepage.jpg)]  bg-cover  flex flex-row item-center justify-center text-color:black relative">
            </section>
        </Transition>
        <Transition appear>
            <section class="lg:max-w-6xl px-2 pt-2 mx-auto bg-white/20 rounded-lg text-black group">
                <a :href="route('products.index')" class="flex flex-row items-center w-fit gap-0">
                    <div class="w-8 sm:w-16 text-right grid grid-col-1 place-content-end">
                        <div class="group-hover:mr-2 transition-margin duration-400" ><ArrowLeftIcon /></div>
                        
                    </div>
                    <div class="text-black font-bold w-fit text-sm sm:text-base">
                        Tutti i prodotti
                    </div>
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
import { ArrowLeftIcon } from 'lucide-vue-next';



const props = defineProps<{
    product: Product,
    cart?: Cart,
}>()

const cartItem = props.cart?.items.find((v) => v.product.id === props.product.id);
if (props.cart) {
    cartItem
}

</script>
