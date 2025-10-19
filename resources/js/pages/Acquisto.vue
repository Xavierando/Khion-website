<script setup lang="ts">
import PageSection from '@/components/ui/section/PageSection.vue';
import { useAuthStore } from '@/stores/auth';
import { ref, watch } from 'vue';
import { useRouter } from 'vue-router';

const props = defineProps<{
    session_id: string,
}>()

const auth = useAuthStore();
const router = useRouter();
const email = ref('');
const password = ref('');

auth.refresh();

</script>

<template>
    <PageSection class="mt-16">

    </PageSection>

</template>
