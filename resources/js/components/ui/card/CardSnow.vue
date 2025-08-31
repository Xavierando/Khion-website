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
    <a :href="product.link">
      <div
        :class="cn('max-w-md w-full h-full mx-auto rounded-md overflow-hidden shadow-md hover:shadow-lg flex flex-col', props.class)">
        <div class="relative h-[350px] bg-center bg-no-repeat bg-contain"
        :style="'background-image: url('+product.default_images?.thumbnail+');'">
          <div v-if="product.quantity === 0"
            class="absolute top-0 right-0 bg-red-500 text-white px-2 py-1 m-2 rounded-md text-sm font-medium">
            SOLD OUT
          </div>
        </div>
        <div class="grow p-4 flex flex-col justify-between">
          <div>
            <h3 class="text-lg font-medium mb-2">{{ product.name }}</h3>
          </div>
          <div class="grow mb-4 flex flex-row flex-wrap justify-between gap-2 items-start">
            <div class="text-gray-600 text-sm bg-blue-200 rounded-sm p-1 px-2" v-for="tag in product.tags"
              :key="tag.id">{{ tag.tag }}</div>
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
    </a>
  </Transition>
</template>
