<template>

    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <AppLayout>
        <section
            class="h-1/5 bg-[url(@images/homepage.png)]  bg-cover  flex flex-row item-center justify-center text-color:black relative">
        </section>
        <section class="lg:max-w-6xl p-5 mx-auto bg-white/20 rounded-lg text-black">
            <h3 class="bg-blue-500 hover:bg-blue-300 text-white font-bold py-2 m-4 px-4 w-fit rounded"><a
                    :href="route('products.index')">Tutti i prodotti</a></h3>
        </section>

        <section>
            <div class="lg:max-w-6xl p-5 mx-auto bg-white/20 rounded-lg text-black">
                <h2 class="text-4xl font-semibold tracking-tight text-gray-900 sm:text-5xl text-center pb-10">{{
                    product.name }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <img class="w-full h-full" :src="product.imageUrl"></img>
                    </div>
                    <div>
                        <div>{{ product.description }}</div>

                        <div class="my-2 grid grid-cols-1 relative p-3">
                            <h2 class="text-xl">Configurations</h2>
                            <div v-for="item in product.configuration.options"
                                class="grid grid-cols-2 group w-fit items-center text-lg">
                                <div>{{ item.name }}</div>
                                <div>
                                    <select @change="event => { item.ref = event.target?.value; updatePrice() }"
                                        class="cursor-pointer p-2">
                                        <option v-for="option of item.options" :value="option.name"
                                            :selected="option.name === item.ref">{{ option.name }}
                                        </option>
                                    </select>
                                </div>

                            </div>
                        </div>
                        <div class="flex flex-row-reverse  items-center ">
                            <button
                                class="cursor-pointer bg-blue-500 hover:bg-blue-300 text-white font-bold py-2 m-4 px-4 w-fit rounded"
                                @click="addToCart()">
                                Acquista
                            </button>
                            <div class="text-lg text-black flex">
                                <div>{{ divPrice }}</div> &euro;
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </AppLayout>
</template>

<script setup lang="ts">
import Card from '@/components/ui/card/CardSnow.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref,inject } from 'vue';
import { Product, ConfOption } from '@/types';
import { useCookies } from '@vueuse/integrations/useCookies';


const props = defineProps<{
    product: Product,
}>()

props.product.configuration.options?.map((v) => { v.ref = (v.options.filter(i => i.price === 0))[0].name; return v })

const divPrice = ref(0);
const findOption = (item: Array<ConfOption>, name: string) => {
    return item.filter((v) => v.name === name)
}
const updatePrice = () => {
    divPrice.value = parseInt(props.product.configuration.options?.reduce(
        (a, i) => a + findOption(i.options, i.ref)[0].price,
        props.product.base_price
    ));
}

updatePrice();

const globalCart = inject('globalCart')
const cookies = useCookies()

const addToCart = () => {

    const product = {
        id: props.product.id,
        name: props.product.name,
        short: props.product.short,
        description: props.product.description,
        imageUrl: props.product.imageUrl,
        price: divPrice.value
    };
    product.options = props.product.configuration.options.map((i) => {
        return {
            name: i.name,
            option: findOption(i.options, i.ref)[0].name,
            price: findOption(i.options, i.ref)[0].price,
        }
    });

    globalCart.value.list.push(product);

    console.log(JSON.stringify(globalCart.value));
    cookies.set('cart', globalCart.value,{path:'/'});
}
</script>
