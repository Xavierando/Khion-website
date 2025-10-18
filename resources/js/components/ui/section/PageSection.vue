<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'
import { Product } from '@/types';

const props = defineProps<{
    class?: HTMLAttributes['class'],
    navigationBack?: Object,
    leftBtn?: { click: Object, label: string },
}>()


</script>

<template>
    <section
        :class='cn(props.class, "tw:flex tw:flex-row tw:item-center tw:justify-center tw:relative tw:transition tw:duration-600")'>

        <div class="tw:max-w-7xl tw:w-full pa-2 tw:mx-auto">
            <v-row justify="space-between">
                <v-col>
                    <v-btn v-if="navigationBack" variant="plain" prepend-icon="$prev" spaced="start"
                        @click="navigationBack">
                        <span class="text-right">
                            <div>Indietro</div>
                        </span>
                    </v-btn>
                </v-col>
                <v-spacer></v-spacer>
                <v-col class="d-flex flex-row-reverse">
                    <v-btn v-if="leftBtn" variant="tonal" spaced="start" color="error" @click="leftBtn.click">
                        <span class="text-right">
                            <div>{{ leftBtn.label }}</div>
                        </span>
                    </v-btn>
                </v-col>
            </v-row>
            <slot />
        </div>
    </section>
</template>