<script setup lang="ts">
import type { HTMLAttributes } from "vue";
import { ref } from "vue";
import { Prodotto } from "@/stores/products";

const expand = ref(true);

const props = defineProps<{
    class?: HTMLAttributes["class"];
    prodotto: Prodotto;
}>();
</script>

<template>
    <v-card @mouseover="expand = false" @mouseleave="expand = true">
        <v-img
            class="fill-height fill-width"
            :src="prodotto.default_images?.thumbnail"
            cover
            aspect-ratio="0.75"
        >
            <div
                v-if="prodotto.quantity <= 0"
                class="ml-auto mr-0 pa-2 px-3 rounded-sm bg-red text-white font-weight-bold"
                style="width: fit-content"
            >
                SOLD OUT
            </div>
        </v-img>
        <v-expand-transition>
            <v-card
                v-show="expand"
                class="mx-auto bg-primary position-absolute bottom-0 right-0 w-100"
            >
                <v-card-title>
                    {{ prodotto.name }}
                </v-card-title>
                <v-card-subtitle>
                    <v-chip
                        class=""
                        v-for="tag in prodotto.tags"
                        :key="tag.id"
                        >{{ tag.tag }}</v-chip
                    >
                </v-card-subtitle>
                <v-card-actions class="justify-space-between">
                    <span class="tw:font-bold tw:text-lg"
                        >{{ prodotto.base_price }} &euro;</span
                    >
                    <v-btn variant="outlined">Dettagli</v-btn>
                </v-card-actions></v-card
            >
        </v-expand-transition>
    </v-card>
</template>
