<template>
    <v-container class="mx-auto">
        <v-row class="w-100">
            <v-col sm="3" cols="12" class="tw:transition-all tw:duration-500">
                <v-card class="elevation-0">
                    <v-row dense>
                        <v-col
                            v-for="img in prodotto.images"
                            lg="6"
                            sm="12"
                            cols="6"
                            class="tw:transition-all tw:duration-500"
                        >
                            <v-dialog fullscreen>
                                <template
                                    v-slot:activator="{ props: activatorProps }"
                                >
                                    <v-img
                                        v-bind="activatorProps"
                                        :src="img.src"
                                        class="cursor-pointer"
                                    ></v-img>
                                </template>

                                <template v-slot:default="{ isActive }">
                                    <v-card
                                        @click="isActive.value = false"
                                        max-height="h-100"
                                    >
                                        <v-img :src="img.src"></v-img>
                                    </v-card>
                                </template>
                            </v-dialog>
                        </v-col>
                    </v-row> </v-card
            ></v-col>
            <v-col sm="9" cols="12" class="tw:transition-all tw:duration-500">
                <v-card class="elevation-0">
                    <v-card-title class="text-h4">
                        {{ prodotto.name }}
                    </v-card-title>
                    <v-card-subtitle class="ga-2 d-flex flex-wrap">
                        <v-chip
                            class="text-subtitle-1"
                            v-for="tag in prodotto.tags"
                            :key="tag.id"
                            >{{ tag.tag }}</v-chip
                        >
                    </v-card-subtitle>
                    <v-card-text class="text-body-1">
                        <vue-markdown :source="prodotto.description" />
                    </v-card-text>
                    <v-card-actions class="justify-end">
                        <BuyQuantitySelector :-id-prodotto="prodotto.id" />
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script setup lang="ts">
import VueMarkdown from "vue-markdown-render";
import BuyQuantitySelector from "@/components/BuyQuantitySelector.vue";
import { Prodotto } from "@/stores/products";

const props = defineProps<{
    prodotto: Prodotto;
}>();
</script>
