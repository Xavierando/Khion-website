<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Cart } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import CardSnowBig from '@/components/ui/card/CardSnowBig.vue';

const props = defineProps<{
    cart: Cart,
}>()

const formAcquisto = useForm({
    products: []
})

function submitAcquista() {
    formAcquisto.post('/checkout');
}

const voidCart = computed(() => props.cart.items.length === 0);
const totalPrice = computed(() => props.cart.items?.reduce((a, v) => a + v.product.base_price * v.quantity, 0) ?? 0);

</script>

<template>

    <Head title="Cart">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <AppLayout>
        <div class="relative h-screen">
            <div class="relative h-screen overflow-scroll">
                <section class="h-16">
                </section>
                <section class="h-16">
                </section>

                <section v-for="item in cart.items" :key="item.id" class="">
                    <CardSnowBig :item="item" :product='item.product' scenario="cart"></CardSnowBig>

                    <!--
                    <Transition appear>
                        <div class="lg:max-w-6xl p-5 mx-auto bg-white/20 rounded-lg text-black">
                            <h3
                                class="text-xl font-semibold tracking-tight text-gray-900 sm:text-5xl text-center pb-10">
                                {{ item.product.name }}
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <img class="w-full h-full" :src="item.product.images[0]?.path" />
                                </div>
                                <div>
                                    <div>{{ item.product.description }}</div>

                                    <div class="flex flex-row-reverse  items-center justify-between mt-3">
                                        <div class="flex flex-row-reverse  items-center ">
                                            <div class="bg-red-600/50 hover:bg-red-600 p-1 rounded-full ml-2"
                                                @click="DeleteCartItem(item.id)">
                                                <TrashIcon class="size-6" aria-hidden="true" />
                                            </div>
                                            <div class="text-lg text-black flex">
                                                <div class="text-xl font-bold px-1">{{ item.product.base_price *
                                                    item.quantity }}</div>
                                                &euro;
                                            </div>
                                        </div>
                                        <div class="flex flex-row-reverse  items-center gap-4 ml-6">
                                            <PlusCircleIcon :class="['h-10 cursor-pointer' ,{'opacity-50' : item.product.quantity === 0}]"
                                                @click="submitUpdate(item.product.id, item.quantity + 1)" />
                                            <div>{{ item.quantity }}</div>
                                            <MinusCircleIcon :class="['h-10 cursor-pointer' ,{'opacity-50' : item.quantity === 0}]"
                                                @click="submitUpdate(item.product.id, item.quantity - 1)" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </Transition>
                    -->
                </section>

                <section class="top-16 h-16" v-if="voidCart">
                    <div class="w-screen">
                        <div
                            class="lg:max-w-6xl md:p-1 mx-auto bg-white/20 rounded-lg text-black flex flex-row-reverse">
                            <div class="text-centered px-6 md:py-2 text-lg text-bold mx-auto items-center">
                                <div class="ml-5">
                                    Il tuo carrello é vuoto
                                    <a :href="route('products.index')">
                                        <button @click="submitAcquista()"
                                            class="rounded-lg bg-blue-600/20 px-2 text-xl font-bold hover:bg-blue-600/50 cursor-pointer ml-2">
                                            Torna allo shop
                                        </button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

            </div>
            <Transition appear>
                <section class="absolute top-16 h-16" v-if="!voidCart">
                    <div class="w-screen">
                        <div
                            class="lg:max-w-6xl md:p-1 mx-auto bg-white/20 rounded-lg text-black flex flex-row-reverse">
                            <div
                                class="md:rounded-xl bg-gray-200 transiction-bg duration-600  px-6 md:py-2 text-lg text-bold md:w-auto w-full flex flex-row-reverse items-center text-right">
                                <div class="ml-5">
                                    <button @click="submitAcquista()"
                                        class="rounded-lg bg-blue-600/20 p-3 text-xl font-bold hover:bg-blue-600/50 cursor-pointer">
                                        Acquista
                                    </button>
                                </div>
                                <div class="flex flex-row  items-center">
                                    <div class="text-xl font-bold px-1">{{ totalPrice }}</div>
                                    <div class=" item-center">&euro;</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </Transition>

        </div>
    </AppLayout>
</template>
