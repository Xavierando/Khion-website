<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'
import Button from '../button/Button.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
  class?: HTMLAttributes['class'],
  action?: Array<Object>,
}>()
</script>

<template>
  <div data-slot="card-action"
    :class="cn('w-full h-full bg-indigo-400/20 z-5 absolute top-0 left-0 flex flex-col align-center justify-evenly group-hover:z-10 group-hover:opacity-100 opacity-0 transition-visibility duration-300', props.class)">
    <div v-for="item in action">
      <div class="w-fit mx-auto ">
        <a :key="item.action" :href="item.url"
          :class="['bg-gray-500 hover:bg-gray-700 text-white', 'rounded-md px-3 py-2 text-lg font-medium', 'p-3']">{{
            item.action }}</a>
      </div>
    </div>
  </div>
</template>
