<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';
import NavBar from './nav/NavBar.vue';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <div class="relative h-screen w-screen">
        <NavBar class="absolute top-0 w-full z-10" />
        <main class="mx-auto h-screen overflow-scroll">
            <slot />
        </main>
    </div>
</template>
