<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'
import CardHeader from './CardHeader.vue';
import CardImg from './CardImg.vue';
import CardTitle from './CardTitle.vue';
import CardContent from './CardContent.vue';
import CardAction from './CardAction.vue';

const props = defineProps<{
  class?: HTMLAttributes['class'],
  bg?: string,
  footer?: string,
  title?: string,
  description?: string,
  action?: Array<Object>,
}>()
</script>

<template>
  <div data-slot="card" :class="cn(
    'bg-white text-card-foreground flex flex-col gap-6 rounded-xl border py-6 shadow-sm text-black relative group mx-auto',
    props.class,
  )
    "
    style='height:650px;width:350px'>

    <CardAction v-if="props.action" :action="props.action">

    </CardAction>

    <CardTitle v-if="props.title" class=" z-7">
      {{ props.title }}
    </CardTitle>

    <CardImg v-if="props.bg" :bg="props.bg" class=" z-7">

    </CardImg>

    <CardContent class=" z-7">
      <slot />
    </CardContent>

  </div>
</template>
