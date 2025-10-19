<script setup lang="ts">
import Card from '@/components/ui/card/CardSnow.vue';
import PageSection from '@/components/ui/section/PageSection.vue';
import { Product, User } from '@/types';
import { useTeamsStore } from '@/stores/teams';
import { useProductsStore } from '@/stores/products';
import { useRouter } from 'vue-router';
import NavBar from '@/components/ui/nav/NavBar.vue';
import CardTeam from '@/components/ui/card/CardTeam.vue';
import { useDisplay } from 'vuetify';

const teams = useTeamsStore();
const products = useProductsStore();

teams.fetchAllTeamsMembers();
products.fetchAllProducts();

const router = useRouter();

</script>

<template>

    <v-theme-provider>
        <v-parallax src="/images/homepage.jpg">

            <Transition appear>
                <PageSection class="h-screen bg-transparent">
                    <v-row class="fill-height align-center">
                        <v-col>
                            <v-img class="mx-auto pa-2 bg-black" rounded="xl" :max-width="600" src="/images/Logo bianco.png">

                            </v-img>
                        </v-col>
                    </v-row>

                </PageSection>
            </Transition>



            <Transition appear>
                <PageSection class="bg-secondary-darken-1  pt-5 pb-5">
                    <h1 class="text-h3 font-weight-bold mb-4 tw:text-center tw:pb-10"> La nostra storia</h1>
                    <v-row class="justify-between align-center">
                        <v-col>
                            <p class="pa-2 text-body-1">Benvenuti in KHION Snowboards, dove la passione incontra la
                                precisione nel cuore
                                delle montagne. Fondata nel 2020 da un gruppo di appassionati di snowboard, il nostro
                                viaggio è iniziato con un amore semplice ma profondo per questo sport e il desiderio di
                                creare qualcosa di veramente eccezionale. Non siamo solo un'azienda; siamo una famiglia
                                di
                                rider, artigiani e sognatori dedicati alla creazione delle migliori tavole da snowboard
                                con
                                cura e amore.</p>

                            <p class="pa-2 text-body-1">Fin dall'inizio, la nostra missione è stata chiara: produrre
                                tavole
                                da snowboard
                                di alta qualità che migliorino l'esperienza di guida rispettando l'ambiente. Ogni tavola
                                che
                                esce dal nostro laboratorio è una testimonianza del nostro impegno per l'eccellenza e la
                                sostenibilità. Crediamo che le migliori discese provengano da tavole che non sono solo
                                performanti, ma anche eco-compatibili.</p>
                        </v-col>
                        <v-col v-if="$vuetify.display.mdAndUp" md="3" lg="2">
                            <img class="tw:rounded-xl" src="@images/homepage.jpg">
                        </v-col>
                    </v-row>
                    <v-row class="justify-between align-center">
                        <v-col v-if="$vuetify.display.mdAndUp" md="3" lg="2">
                            <img class="tw:rounded-xl" src="@images/homepage.jpg">
                        </v-col>
                        <v-col>
                            <p class="pa-2 text-body-1">Benvenuti in KHION Snowboards, dove la passione incontra la
                                precisione nel cuore
                                delle montagne. Fondata nel 2020 da un gruppo di appassionati di snowboard, il nostro
                                viaggio è iniziato con un amore semplice ma profondo per questo sport e il desiderio di
                                creare qualcosa di veramente eccezionale. Non siamo solo un'azienda; siamo una famiglia
                                di
                                rider, artigiani e sognatori dedicati alla creazione delle migliori tavole da snowboard
                                con
                                cura e amore.</p>

                            <p class="pa-2 text-body-1">Fin dall'inizio, la nostra missione è stata chiara: produrre
                                tavole
                                da snowboard
                                di alta qualità che migliorino l'esperienza di guida rispettando l'ambiente. Ogni tavola
                                che
                                esce dal nostro laboratorio è una testimonianza del nostro impegno per l'eccellenza e la
                                sostenibilità. Crediamo che le migliori discese provengano da tavole che non sono solo
                                performanti, ma anche eco-compatibili.</p>
                        </v-col>
                    </v-row>
                </PageSection>
            </Transition>


            <PageSection class="bg-secondary-lighten-2 pt-5 pb-5 mt-10">
                <h1 class="text-h3 font-weight-bold mb-4 tw:text-center tw:pb-10">
                    I nostri prodotti
                </h1>
                <v-row>
                    <v-col v-for="product in products.hightlight" cols="12" sm="6" md="3">
                        <Card :prodotto="product" :key="product.id"
                            @click="router.push({ path: '/prodotti/' + product.id })">
                        </Card>
                    </v-col>
                </v-row>

            </PageSection>



            <PageSection class="bg-secondary-lighten-3 pt-5 pb-5">
                <h1 class="text-h3 font-weight-bold mb-4 tw:text-center tw:pb-10">
                    Incontra il nostro Team
                </h1>
                <div class="tw:grid tw:grid-cols-1 tw:md:grid-cols-2 tw:gap-4 tw:justify-items-center">
                    <CardTeam v-for="team in teams.teamsMember" :key="team.name" :user="team"></CardTeam>
                </div>
            </PageSection>
        </v-parallax>
    </v-theme-provider>
</template>
