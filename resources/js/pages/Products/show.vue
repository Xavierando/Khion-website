<script setup lang="ts">
import Card from '@/components/ui/card/CardSnow.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import Select from '@/components/ui/input/Select.vue';

const props = defineProps<{
  product?: Array<Object>,
}>()
</script>

<template>

    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <AppLayout>
        <section
            class="h-1/5 bg-[url(@images/homepage.png)]  bg-cover  flex flex-row item-center justify-center text-color:black relative">
        </section>
        <section
            class="lg:max-w-6xl p-5 mx-auto bg-white/20 rounded-lg text-black">
            <h3 class="bg-blue-500 hover:bg-blue-300 text-white font-bold py-2 m-4 px-4 w-fit rounded"><a :href="route('products.index')">Tutti i prodotti</a></h3>
        </section>

        <section>
            <div class="lg:max-w-6xl p-5 mx-auto bg-white/20 rounded-lg text-black">
                <h2 class="text-4xl font-semibold tracking-tight text-gray-900 sm:text-5xl text-center pb-10">{{ product.name }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <img class="w-full h-full" :src="product.imageUrl" ></img>
                    </div>
                    <div>
                        <div>{{ product.description }}</div>

                        <div class="my-2">
                            <Select v-for="item in product.configuration.options" :items="item"></Select>
                        </div>
                        <div class="flex flex-row-reverse">
                            <button class="cursor-pointer bg-blue-500 hover:bg-blue-300 text-white font-bold py-2 m-4 px-4 w-fit rounded">Acquista</button>
                        </div>
                        
                    </div>
                </div>
            </div>
        </section>

    </AppLayout>
</template>
