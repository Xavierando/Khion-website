<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'
import { Product } from '@/types';

const props = defineProps<{
    class?: HTMLAttributes['class'],
    navigationBack?: Object,
}>()


</script>

<template>
        <section
            :class='cn(props.class, "tw:flex tw:flex-row tw:item-center tw:justify-center tw:relative tw:transition tw:duration-600")'>

            <div class="tw:max-w-7xl tw:w-full pa-2 tw:mx-auto">

                <v-btn v-if="navigationBack" variant="plain" prepend-icon="$prev" spaced="start"
                    @click="navigationBack">
                    <span class="text-right">
                        <div>Indietro</div>
                    </span>
                </v-btn>
                <slot />
            </div>
        </section>
</template>