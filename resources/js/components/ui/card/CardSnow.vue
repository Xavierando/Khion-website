<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'
import { Product } from '@/types';

const props = defineProps<{
  class?: HTMLAttributes['class'],
  product: Product,
}>()
</script>

<template>
  <Transition appear>
    <div
      :class="cn('max-w-md mx-auto rounded-md overflow-hidden shadow-md hover:shadow-lg grid grid-col-1 grid-template-columns: max-content 1fr', props.class)">
      <div class="relative">
        <img class="w-full" :src="product.default_images?.src" alt="Product Image">
        <div v-if="product.quantity === 0" class="absolute top-0 right-0 bg-red-500 text-white px-2 py-1 m-2 rounded-md text-sm font-medium">
          SOLD OUT
        </div>
      </div>
      <div class="p-4 flex flex-col justify-between">
        <div>
          <h3 class="text-lg font-medium mb-2">{{ product.name }}</h3>
        </div>
        <div class="grow">
          <p class="text-gray-600 text-sm mb-4">{{ product.short }}</p>
        </div>
        <div class="flex items-center justify-between">
          <span class="font-bold text-lg">{{ product.base_price }} &euro;</span>
          <a :href="product.link">
            <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded cursor-pointer">
              Dettagli
            </button>
          </a>
        </div>
      </div>
    </div>
  </Transition>
</template>
