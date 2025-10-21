<script setup lang="ts">
import Card from '@/components/ui/card/CardSnow.vue';
import PageSection from '@/components/ui/section/PageSection.vue';
import { useProductsStore } from '@/stores/products';
import { useSearchStore } from '@/stores/search';
import { useRouter } from 'vue-router';

const products = useProductsStore();
const search = useSearchStore();

products.fetchAllProducts();

const router = useRouter();
</script>

<template>
  <v-theme-provider>
    <PageSection class="mt-16">
      <v-sheet class="d-flex justify-center">
        <div class="flex-grow-1" style="max-width: 600px">
          <v-text-field
            variant="solo"
            prepend-icon="mdi-magnify"
            v-model="search.tearm"
          ></v-text-field>
        </div>
        <div cols="1">
          <v-tooltip text="Includi Prodotti Soldout">
            <template v-slot:activator="{ props }">
              <v-switch
                v-bind="props"
                color="primary"
                v-model="search.includeSoldOut"
              ></v-switch>
            </template>
          </v-tooltip>
        </div>
      </v-sheet>
    </PageSection>

    <PageSection>
      <div class="mr-auto ml-auto">
        <div
          class="tw:grid tw:grid-cols-1 tw:md:grid-cols-2 tw:lg:grid-cols-3 tw:xl:grid-cols-4 tw:gap-4 tw:place-self-center"
        >
          <Card
            v-for="product in products.filtered"
            :prodotto="product"
            :key="product.id"
            @click="router.push({ path: '/prodotti/' + product.id })"
          >
          </Card>
        </div>
      </div>
    </PageSection>
  </v-theme-provider>
</template>
