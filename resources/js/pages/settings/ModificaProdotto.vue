<template>
    <PageSection class="mt-16">
    </PageSection>
    <PageSection v-if="prodotto" :navigation-back="() => router.push({ name: 'gestisciProdotti' })" :left-btn="{label:'Salva',click:() => !prodotto || prodotto.update()}">
        <v-row align="start" justify="space-between" class=" fill-width" no-gutters>
            <v-col cols="12" sm="4" :md="3" class="pa-1">
                <v-text-field label="Nome" v-model="prodotto.name" />
            </v-col>
            <v-col cols="12" sm="4" :md="3" class="pa-1">
                <v-text-field label="Codice" v-model="prodotto.code" />
            </v-col>
            <v-col cols="12" sm="2" :md="1" class="pa-1">
                <v-text-field label="Quantita" v-model="prodotto.base_quantity" />
            </v-col>
            <v-col cols="12" sm="2" :md="1" class="pa-1">
                <v-text-field label="Prezzo" v-model="prodotto.base_price" />
            </v-col>
            <v-col cols="12" :md="3" class="pa-1">
                <SelectTags :id="id"></SelectTags>
            </v-col>
        </v-row>

        <v-textarea label="bio" v-model="prodotto.description"></v-textarea>
        <SelectImages :id="id"></SelectImages>

        <v-card>
            <v-tabs v-model="tab" bg-color="primary">
                <v-tab value="one">Card Piccola</v-tab>
                <v-tab value="two">Dettagli Prodotto</v-tab>
            </v-tabs>

            <v-card-text>
                <v-tabs-window v-model="tab">
                    <v-tabs-window-item value="one">
                        <v-sheet class="mx-auto" :max-width="450">
                            <CardSnow :product="prodotto"></CardSnow>
                        </v-sheet>
                    </v-tabs-window-item>

                    <v-tabs-window-item value="two">
                        <ShowProductContainer :prodotto="prodotto" />
                    </v-tabs-window-item>

                </v-tabs-window>
            </v-card-text>
        </v-card>
    </PageSection>
</template>

<script setup lang="ts">
import ShowProductContainer from '@/components/ShowProductContainer.vue';
import CardSnow from '@/components/ui/card/CardSnow.vue';
import PageSection from '@/components/ui/section/PageSection.vue';
import SelectImages from '@/components/ui/select/SelectImages.vue';
import SelectTags from '@/components/ui/select/SelectTags.vue';
import { useProductsStore } from '@/stores/products';
import { computed, ref } from 'vue';
import { useRouter } from 'vue-router';
const props = defineProps<{
    id: string,
}>()

const router = useRouter();
const tab = ref('one');


const prodottiStore = useProductsStore()
const prodotto = computed(() => prodottiStore.findProduct(Number(props.id)));



</script>
