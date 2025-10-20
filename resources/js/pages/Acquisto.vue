<template>
    <PageSection class="h-screen">
        <v-row v-if="order" class="fill-height fill-width align-center justify-center">
            <v-col v-if="order.status === 'paid'">
                <v-card class="bg-success mx-auto" style="width:fit-content">
                    <v-card-title>
                        L'ordine numero {{ order.id }} é stato completato
                    </v-card-title>
                </v-card>
            </v-col>
            <v-col v-if="order.status === 'pending'">
                <v-card class="bg-warning mx-auto" :width="300">
                    <v-card-title>
                        Il pagamento in sospeso
                    </v-card-title>
                    <v-card-actions class="justify-end">
                        <v-btn variant="tonal" @click="handleRitentaPagamento">Ritenta Pagamento</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </PageSection>
</template>

<script setup lang="ts">
import PageSection from '@/components/ui/section/PageSection.vue';
import { computed, ref } from 'vue';
import { useOrdersStore } from '@/stores/orders';

const props = defineProps<{
    session_id: string,
}>()

const orders = useOrdersStore();

orders.fetch(props.session_id);
const order = computed(() => orders.find(Number(props.session_id)));

const handleRitentaPagamento = async () => {
    if (order.value) {
        const url = await order.value.checkoutUrl()
        if (url) {
            window.location.href = url
        }
    }
}


</script>