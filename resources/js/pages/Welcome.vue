<script setup lang="ts">
import Card from '@/components/ui/card/CardSnow.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Product,User } from '@/types';

const props = defineProps<{
    teams?: Array<User>,
    products?: Array<Product>,
}>()
</script>

<template>

    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <AppLayout>
        <section
            class="h-screen bg-[url(@images/homepage.png)]  bg-cover  flex flex-row item-center justify-center text-color:black relative">
            <div class="mx-auto h-full grid place-items-center">
                <div class="lg:min-w-md p-12 bg-white/20 rounded-lg">
                    <h1 class="text-7xl text-black font-bold">KHION</h1>
                    <p class="text-black ml-5 font-bold">BEST SNOW EVER</p>
                </div>
            </div>
        </section>

        <section>
            <div class="lg:max-w-6xl p-5 mx-auto bg-white/20 rounded-lg text-black  py-24">
                <h2 class="text-4xl font-semibold tracking-tight text-gray-900 sm:text-5xl text-center pb-10">La nostra
                    storia</h2>
                <div class="flex flex-row item-center justify-center">
                    <div>

                        <p class="p-2">Benvenuti in KHION Snowboards, dove la passione incontra la precisione nel cuore
                            delle montagne. Fondata nel 2020 da un gruppo di appassionati di snowboard, il nostro
                            viaggio è iniziato con un amore semplice ma profondo per questo sport e il desiderio di
                            creare qualcosa di veramente eccezionale. Non siamo solo un'azienda; siamo una famiglia di
                            rider, artigiani e sognatori dedicati alla creazione delle migliori tavole da snowboard con
                            cura e amore.</p>

                        <p class="p-2">Fin dall'inizio, la nostra missione è stata chiara: produrre tavole da snowboard
                            di alta qualità che migliorino l'esperienza di guida rispettando l'ambiente. Ogni tavola che
                            esce dal nostro laboratorio è una testimonianza del nostro impegno per l'eccellenza e la
                            sostenibilità. Crediamo che le migliori discese provengano da tavole che non sono solo
                            performanti, ma anche eco-compatibili.</p>
                    </div>
                    <div class="hidden lg:block">
                        <img  class="rounded-xl" src="@images/homepage.png">
                    </div>
                </div>
                <div class="flex flex-row item-center justify-center flex-row-reverse mt-2">
                    <div>

                        <p class="p-2">Benvenuti in KHION Snowboards, dove la passione incontra la precisione nel cuore
                            delle montagne. Fondata nel 2020 da un gruppo di appassionati di snowboard, il nostro
                            viaggio è iniziato con un amore semplice ma profondo per questo sport e il desiderio di
                            creare qualcosa di veramente eccezionale. Non siamo solo un'azienda; siamo una famiglia di
                            rider, artigiani e sognatori dedicati alla creazione delle migliori tavole da snowboard con
                            cura e amore.</p>

                        <p class="p-2">Fin dall'inizio, la nostra missione è stata chiara: produrre tavole da snowboard
                            di alta qualità che migliorino l'esperienza di guida rispettando l'ambiente. Ogni tavola che
                            esce dal nostro laboratorio è una testimonianza del nostro impegno per l'eccellenza e la
                            sostenibilità. Crediamo che le migliori discese provengano da tavole che non sono solo
                            performanti, ma anche eco-compatibili.</p>
                    </div>
                    <div class="hidden lg:block">
                        <img class="rounded-xl" src="@images/homepage.png">
                    </div>
                </div>
            </div>
        </section>


        <section>
            <div class="lg:max-w-6xl p-5 mx-auto bg-white/20 rounded-lg text-black">
                <h2 class="text-4xl font-semibold tracking-tight text-gray-900 sm:text-5xl text-center pb-10">I nostri
                    prodotti</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <Card v-for="product in products" :product="product" :key="product.id">
                    </Card>
                </div>
            </div>
        </section>

        <section>
            <div class="bg-white py-24 sm:py-32">
                <div class="mx-auto max-w-7xl px-6 lg:px-8">
                    <div class="mx-auto max-w-2xl sm:text-center">
                        <h2 class="text-4xl font-semibold tracking-tight text-gray-900 sm:text-5xl">Incontra il nostro
                            Team</h2>
                    </div>
                    <ul role="list"
                        class="mx-auto mt-20 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-20 sm:grid-cols-2 lg:max-w-4xl xl:max-w-none">
                        <li v-for="team in props.teams" :key="team.id" class="flex flex-col items-start gap-x-10 gap-y-6 xl:flex-row">
                            <div class="flex-none">
                                <div class="relative aspect-square w-56 overflow-hidden rounded-full bg-gray-200">
                                    <span aria-hidden="true"
                                        class="absolute inset-0 h-full w-full rounded-full ring-1 ring-inset ring-gray-900/10"></span>
                                    <img width="400" height="400" class="h-full w-full object-cover"
                                        :src="`/images/${team.url}`" alt="" />
                                </div>
                            </div>
                            <div class="flex-auto">
                                <h3 class="text-2xl font-semibold leading-9 tracking-tight text-gray-900">{{ team.name
                                    }}
                                </h3>
                                <p class="text-lg leading-8 text-gray-600">{{ team.role }}</p>
                                <p class="mt-4 text-base leading-7 text-gray-600">{{ team.bio }}
                                </p>
                                <ul role="list" class="mt-6 flex gap-x-5">
                                    <li>
                                        <a class="text-gray-400 hover:text-gray-500" href="#">
                                            <span class="sr-only">X</span>
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                aria-hidden="true" fill="currentColor">
                                                <path
                                                    d="M14.095479,10.316482L22.286354,1h-1.940718l-7.115352,8.087682L7.551414,1H1l8.589488,12.231093L1,23h1.940717  l7.509372-8.542861L16.448587,23H23L14.095479,10.316482z M11.436522,13.338465l-0.871624-1.218704l-6.924311-9.68815h2.981339  l5.58978,7.82155l0.867949,1.218704l7.26506,10.166271h-2.981339L11.436522,13.338465z">
                                                </path>
                                            </svg>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="text-gray-400 hover:text-gray-500" href="#">
                                            <span class="sr-only">LinkedIn</span>
                                            <svg class="h-5 w-5" fill="currentColor" version="1.1"
                                                xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512"
                                                xml:space="preserve" aria-hidden="true">
                                                <path style="display: inline; fill-rule: evenodd; clip-rule: evenodd"
                                                    d="M116.504,500.219V170.654H6.975v329.564H116.504 L116.504,500.219z M61.751,125.674c38.183,0,61.968-25.328,61.968-56.953c-0.722-32.328-23.785-56.941-61.252-56.941 C24.994,11.781,0.5,36.394,0.5,68.722c0,31.625,23.772,56.953,60.53,56.953H61.751L61.751,125.674z M177.124,500.219 c0,0,1.437-298.643,0-329.564H286.67v47.794h-0.727c14.404-22.49,40.354-55.533,99.44-55.533 c72.085,0,126.116,47.103,126.116,148.333v188.971H401.971V323.912c0-44.301-15.848-74.531-55.497-74.531 c-30.254,0-48.284,20.38-56.202,40.08c-2.897,7.012-3.602,16.861-3.602,26.711v184.047H177.124L177.124,500.219z">
                                                </path>
                                            </svg>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
