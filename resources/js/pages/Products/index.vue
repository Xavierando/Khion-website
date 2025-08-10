<script setup lang="ts">
import Card from '@/components/ui/card/CardSnow.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Product } from '@/types';

const props = defineProps<{
    products?: Array<Product>,
}>()
</script>

<template>

    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <AppLayout>
        <Transition appear>
            <section
                class="h-1/5 bg-[url(@images/homepage.png)]  bg-cover  flex flex-row item-center justify-center text-color:black relative">
            </section>
        </Transition>

        <Transition appear>
            <section>
                <div class="lg:max-w-6xl p-5 mx-auto bg-white/20 rounded-lg text-black">
                    <h2 class="text-4xl font-semibold tracking-tight text-gray-900 sm:text-5xl text-center pb-10">I
                        nostri
                        prodotti</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <Card v-for="product in props.products" :product="product" :key="product.id">
                        </Card>
                    </div>
                </div>
            </section>
        </Transition>

    </AppLayout>
</template>
