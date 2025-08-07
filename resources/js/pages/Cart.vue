<script setup lang="ts">
import Card from '@/components/ui/card/CardSnow.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Product, User } from '@/types';
import { computed, inject } from 'vue';


const props = defineProps<{
    teams?: Array<User>,
    products?: Array<Product>,
}>()

const globalCart = inject('globalCart');

console.log(globalCart)

const totalPrice = computed( () => globalCart.value.list?.reduce((a,v) => a + v.price,0) ?? 0)

const formAcquisto = useForm({
    products: []
})

function submitAcquista (){
    formAcquisto.products = globalCart.value.list;
    formAcquisto.post('/checkout');
}

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

                <section v-for="item in globalCart.list" class="">
                    <div class="lg:max-w-6xl p-5 mx-auto bg-white/20 rounded-lg text-black">
                        <h2 class="text-4xl font-semibold tracking-tight text-gray-900 sm:text-5xl text-center pb-10">{{
                            item.name }}</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <img class="w-full h-full" :src="item.imageUrl"></img>
                            </div>
                            <div>
                                <div>{{ item.description }}</div>

                                <div class="my-2 grid grid-cols-1 relative p-3">
                                    <h2 class="text-xl">Configurations</h2>
                                    <div v-for="opt in item.options"
                                        class="grid grid-cols-2 group w-fit items-center text-lg gap-2">
                                        <div>{{ opt.name }}</div>
                                        <div class="font-bold">
                                            {{ opt.option }}
                                        </div>

                                    </div>
                                </div>
                                <div class="flex flex-row-reverse  items-center ">
                                    <div class="text-lg text-black flex">
                                        <div  class="text-xl font-bold px-1">{{ item.price }}</div> &euro;
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

            </div>
            <section class="absolute top-16 h-16">
                <div class="w-screen">
                    <div class="lg:max-w-6xl md:p-1 mx-auto bg-white/20 rounded-lg text-black flex flex-row-reverse">
                        <div
                            class="md:rounded-xl bg-gray-200 transiction-bg duration-600  px-6 md:py-2 text-lg text-bold md:w-auto w-full flex flex-row-reverse items-center text-right">
                            <div class="ml-5">
                                <button
                                    @click="submitAcquista()"
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

        </div>
    </AppLayout>
</template>
