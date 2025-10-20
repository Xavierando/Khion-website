<template>
    <PageSection class="mt-16">
    </PageSection>
    <PageSection v-if="ordine" :navigation-back="() => $router.push({ name: 'gestisciOrdini' })">
        <v-sheet v-if="ordine.status === 'pending'" cols="12" class="pa-1 d-flex justify-end">
            <v-btn color="warning">Ritenta Pagamento</v-btn>
        </v-sheet>
        <h1 class="text-h5 mt-5">Ordine numero {{ ordine.id }}<v-chip class="ml-2">{{ ordine.status }}</v-chip></h1>
        <v-list lines="one">

            <v-list-subheader inset>prodotti</v-list-subheader>
            <v-list-item v-for="item in ordine.getItemsList()" :key="item.id" prepend-avatar="">
                <template v-slot:prepend  v-if="typeof item.product !== 'boolean'">
                    <v-img v-if="item.product.default_images" color="grey-lighten-1 mr-3"
                        :src="item.product.default_images.src" :height="50" :width="50" rounded="circle">
                    </v-img>
                </template>
                <v-list-item-title v-if="typeof item.product !== 'boolean'">
                    {{ item.product.name }}
                </v-list-item-title>

            </v-list-item>
            <v-list-item>
                Totale: {{ ordine.total }} &euro;
            </v-list-item>
        </v-list>

        <v-sheet v-if="ordine.status === 'pending'" cols="12" class="pa-1 d-flex justify-end">
            <v-btn color="error">Annulla Ordine</v-btn>
        </v-sheet>

    </PageSection>
</template>

<script setup lang="ts">
import ShowProductContainer from '@/components/ShowProductContainer.vue';
import CardSnow from '@/components/ui/card/CardSnow.vue';
import PageSection from '@/components/ui/section/PageSection.vue';
import SelectImages from '@/components/ui/select/SelectImages.vue';
import SelectTags from '@/components/ui/select/SelectTags.vue';
import { useOrdersStore } from '@/stores/orders';
import { useProductsStore } from '@/stores/products';
import { computed, ref } from 'vue';
import { useRouter } from 'vue-router';
const props = defineProps<{
    id: string,
}>()

const ordiniStore = useOrdersStore();
ordiniStore.fetch(props.id);


const ordine = computed(() => ordiniStore.find(Number(props.id)));

/*

  id: number;
  status: string;
  statusOptions: string;
  total: number;
  items: CartItem[];
  setStatus(newStatus: string): Promise<boolean>;
  checkoutUrl(): Promise<string|false> ;
  sortByStatusAndDate(OrdineA: Ordine, OrdineB: Ordine): number;
  delete(): void
  deleteFromServer(): Promise<boolean>
  deleteFromLocalStore(): void
*/

</script>
