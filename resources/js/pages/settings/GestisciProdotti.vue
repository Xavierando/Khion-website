<template>
    <PageSection class="mt-16">
    </PageSection>
    <PageSection>
        <v-list lines="one">

            <v-list-subheader inset>Gestisci i tuoi prodotti</v-list-subheader>
            <v-list-item v-for="prodotto in prodottiStore.all" :key="prodotto.id" :title="prodotto.name"
                :subtitle="prodotto.code" prepend-avatar="">
                <template v-slot:prepend>
                    <v-img v-if="prodotto.default_images" color="grey-lighten-1" :src="prodotto.default_images.src" :height="50" :width="50">
                    </v-img>
                </template>

                <template v-slot:append>
                    <v-btn color="primary" icon="mdi-pencil" variant="text" @click="handleEdit(prodotto.id)"></v-btn>
                    <v-btn color="error" icon="mdi-delete" variant="text"></v-btn>
                </template>
            </v-list-item>
            <v-list-item key="new">
                <v-btn prepend-icon="mdi-plus-circle" color="primary" @click="handleAggiungiNuovoProdotto">Aggiungi
                    Prodotto</v-btn>
            </v-list-item>
        </v-list>
    </PageSection>
</template>

<script setup lang="ts">
import PageSection from '@/components/ui/section/PageSection.vue';
import { useAuthStore } from '@/stores/auth';
import { useProductsStore } from '@/stores/products';
import { useRouter } from 'vue-router';

const prodottiStore = useProductsStore()
const router = useRouter()


const auth = useAuthStore();
const redirect = () => {
    if (!auth.user.isAdmin) {
        const router = useRouter()
        router.push({ path: '/' })
    }
}
auth.refresh().then(redirect)


const handleEdit = (id: number) => {
    router.push({ name: 'editProdotto', params: { id } })
}

const handleAggiungiNuovoProdotto = async () => {
    const id = await prodottiStore.createProduct();
    if (id) {
        handleEdit(id);
    }
}
</script>