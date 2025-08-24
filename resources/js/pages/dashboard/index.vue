<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

</script>



<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Dashboard" />
        <div class="md:grid md:grid-cols-2 md:h-screen lg:max-w-6xl p-5 mx-auto bg-white/20 rounded-lg text-black"
            style="grid-template-columns: 250px 1fr">
            <div class="md:block hidden w-full">
                <ul class="mt-24 w-full">
                    <li class="mt-1 w-full"><a class="w-full hover:bg-gray-200/60 transtion duration-300 cursor-pointer p-1 rounded-sm" :href="route('dashboard.user.edit')">Dati personali</a></li>
                    <li class="mt-1 w-full"><a class="w-full hover:bg-gray-200/60 transtion duration-300 cursor-pointer p-1 rounded-sm" :href="route('dashboard.order')">Ordini</a></li>
                </ul>

            </div>
            <div class="md:grid md:grid-cols-1 divide-y md:overflow-scroll">
                <slot />
            </div>
        </div>
    </AppLayout>
</template>
