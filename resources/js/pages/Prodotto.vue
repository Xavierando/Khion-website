<script setup lang="ts">
import PageSection from "@/components/ui/section/PageSection.vue";
import { useProductsStore } from "@/stores/products";
import { useRouter } from "vue-router";
import { computed } from "vue";
import ShowProductContainer from "@/components/ShowProductContainer.vue";

const props = defineProps<{
    id: string;
}>();

const prodotti = useProductsStore();
const router = useRouter();
const prodotto = computed(() => prodotti.findProduct(parseInt(props.id)));
prodotti.fetchProduct(props.id);
</script>

<template>
    <v-theme-provider>
        <div class="mt-16" />
        <PageSection
            v-if="prodotto"
            :navigation-back="() => router.push({ path: '/prodotti' })"
        >
            <ShowProductContainer :prodotto="prodotto" />
        </PageSection>
    </v-theme-provider>
</template>
