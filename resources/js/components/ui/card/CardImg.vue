<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'
import { computed } from 'vue'


const props = defineProps<{
  class?: HTMLAttributes['class'],
  bg: string
}>()

var bg = computed(() => `@images/${props.bg}`);
</script>

<template>
  <div data-slot="card-img" :class="cn('leading-none font-semibold  bg-[url(' + bg + ')] bg-center h-100 bg-cover', props.class)">
  </div>
</template>
