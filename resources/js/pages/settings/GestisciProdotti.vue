<template>
    <PageSection class="mt-16">
    </PageSection>
    <PageSection>
        <v-list lines="one">

            <v-list-subheader inset>Gestisci i tuoi prodotti</v-list-subheader>
            <v-list-item v-for="product in prodottiStore.list" :key="product.id" :title="product.name"
                :subtitle="product.code" prepend-avatar="">
                <template v-slot:prepend>
                    <v-img color="grey-lighten-1" :src="product.default_images.src" :height="50" :width="50">
                    </v-img>
                </template>

                <template v-slot:append>
                    <v-btn color="primary" icon="mdi-pencil" variant="text" @click="handleEdit(product.id)"></v-btn>
                    <v-btn color="error" icon="mdi-delete" variant="text"></v-btn>
                </template>
            </v-list-item>
        </v-list>
    </PageSection>
</template>

<script setup lang="ts">
import PageSection from '@/components/ui/section/PageSection.vue';
import { useProductsStore } from '@/stores/products';
import { useRouter } from 'vue-router';

const prodottiStore = useProductsStore()
const router = useRouter()


const handleEdit = (id: number) => {
    router.push({ name: 'editProdotto', params: { id } })
}
</script>