<template>
  <PageSection class="mt-16"> </PageSection>
  <PageSection>
    <v-list lines="one">
      <v-list-subheader inset>i miei ordini</v-list-subheader>
      <v-list-item v-for="ordine in ordini" :key="ordine.id">
        <v-card
          elevation="16"
          class="d-flex justify-space-between w-100"
          @click="handleEdit(ordine.id)"
        >
          <div>
            <v-card-title>Ordine {{ ordine.id }}</v-card-title>
            <v-card-subtitle>{{ ordine.status }}</v-card-subtitle>
          </div>
        </v-card>
      </v-list-item>
    </v-list>
  </PageSection>
</template>

<script setup lang="ts">
import PageSection from '@/components/ui/section/PageSection.vue';
import { useAuthStore } from '@/stores/auth';
import { useOrdersStore } from '@/stores/orders';
import { useProductsStore } from '@/stores/products';
import { computed } from 'vue';
import { useRouter } from 'vue-router';

const auth = useAuthStore();

const redirect = () => {
  if (!auth.loggedIn) {
    const router = useRouter();
    router.push({ path: '/' });
  }
};

auth.refresh().then(redirect);

const ordiniStore = useOrdersStore();
const router = useRouter();

ordiniStore.fetchAll();
const ordini = computed(() => ordiniStore.all);

const handleEdit = (id: number) => {
  router.push({ name: 'editOrdine', params: { id } });
};
</script>
