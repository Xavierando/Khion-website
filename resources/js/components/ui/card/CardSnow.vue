<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { ref } from 'vue';
import { Prodotto } from '@/stores/products';


const expand = ref(true)

const props = defineProps<{
  class?: HTMLAttributes['class'],
  product: Prodotto,
}>()
</script>

<template>
  <v-card @mouseover="expand = false" @mouseleave="expand = true">
    <v-img class="tw:w-full" :src="product.default_images?.thumbnail" />
    <v-expand-transition>
      <v-card v-show="expand" class="mx-auto bg-primary position-absolute bottom-0 right-0 w-100">
        <v-card-title>
          {{ product.name }}
        </v-card-title>
        <v-card-subtitle>
          <v-chip class="" v-for="tag in product.tags" :key="tag.id">{{ tag.tag }}</v-chip>
        </v-card-subtitle>
        <v-card-actions class="justify-space-between">
          <span class="tw:font-bold tw:text-lg">{{ product.base_price }} &euro;</span>
          <v-btn  variant="outlined">Dettagli</v-btn>
        </v-card-actions></v-card>
    </v-expand-transition>
  </v-card>
</template>
