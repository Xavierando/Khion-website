<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Dialog v-model="ConfermaAzione" @changeStatus="(status) => validateChange(status)" />
        <section id="personal"
            class="md:h-screen rounded-xl border border-sidebar-border/70 dark:border-sidebar-border pl-5 flex flex-col divide-y">
            <h2 class="mt-24 text-xl text-bold p-3">Ordini</h2>
            <div v-for="order in props.orders.data" :key="order.id" class="w-full bg-gray-100/10">
                <div class="flex flex-row justify-between p-2 mx-1 items-center">
                    <div class="flex flex-row text-sm md:text-md items-center">
                        <div>Ordine {{ order.id }}</div>
                        <div class="md:text-sm text-xs ml-4 px-2 rounded-full bg-green-200 font-bold items-center">{{
                            order.status }}</div>
                    </div>
                    <div class="flex flex-row">
                        <div v-if="page.props.auth.user.isAdmin" class="mx-3 flex flex-row gap-2">
                            <button class="text-sm font-bold bg-red-200 p-1 px-2 cursor-pointer hover:bg-red-400 transiction-bg duration-200"
                                v-for="bstatus in order.statusOptions" :key="bstatus"
                                @click="changeStatus(bstatus, order.id)">{{ bstatus }}</button>
                        </div>
                        <div class="font-bold">{{ order.total }}</div>&euro;
                    </div>
                </div>
                <div class="flex flex-col ml-3">
                    <div v-for="prod in order.items" :key="prod.product.id"
                        class="grid grid-cols-2 md:w-fit items-center">
                        <div class="mr-2 text-md md:text-sm font-bold ">{{ prod.product.name }}</div>
                        <div class="grid grid-cols-2 divide-y my-1">
                            <div v-for="conf in prod.product.configuration" :key="conf.name"
                                class="bg-gray-100 col-span-2 grid grid-cols-subgrid gap-y-2">
                                <div class="px-2 text-sm font-normal">
                                    {{ conf.name }}
                                </div>
                                <div class="px-2 font-bold ">
                                    {{ conf.option }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <TailwindPagination :data="props.orders" @pagination-change-page="changePage" />
        </section>
    </AppLayout>
</template>

<script setup lang="ts">
import { CartItem, type BreadcrumbItem } from '@/types';
import AppLayout from '../index.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { provide, ref } from 'vue';
import { useDropZone } from '@vueuse/core';
import { TailwindPagination } from 'laravel-vue-pagination';
import Dialog from '@/components/DialogConfirmAction.vue';

interface Order {
    id: number;
    status: string;
    total: number;
    items: CartItem[];
    statusOptions: string;
}

const props = defineProps<{
    orders: { data: Array<Order>, meta: { path: string } },
}>()

const ConfermaAzione = ref(false)
provide('ConfermaCarrello', ConfermaAzione)


var tempAction: { id: number; status: string; } | null = null;

function validateChange(action: boolean) {
    if (action && tempAction !== null) {
        useForm({ status: tempAction.status }).put(route('dashboard.order.update', { order: tempAction.id }));

    }
    tempAction = null;
}

function changePage(page: string) {
    const path = props.orders.meta.path;
    //document.location.href = path + '&page=' + page;
    useForm({}).get(path + '?page=' + page, {
        onSuccess: () => {
            console.log('ok')
        }
    })
}

function changeStatus(status: string, id: number) {
    //useForm({ status: status }).put(route('dashboard.order.update', { order: id }));
    tempAction = {
        status,
        id
    }
    ConfermaAzione.value = true;
}


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const page = usePage();
const altPic = ref();

const UserData = useForm({
    name: page.props.auth.user?.name,
    role: page.props.auth.user?.role,
    bio: page.props.auth.user?.bio,
})

const UserImg = useForm<{
    _method: string;
    pic: null | File;
}>({
    _method: 'put',
    pic: null,
})

const UserPsw = useForm({
    oldpsw: '',
    newpsw: '',
})

const dropZonePic = ref<HTMLDivElement>()

function onDrop(files: File[] | null) {
    if (files) {
        UserImg.pic = files[0];
        submitUserImg();
    }
}

useDropZone(dropZonePic, {
    onDrop,
    dataTypes: ['image/jpeg', 'image/png'],
    preventDefaultForUnhandled: false,
})

const successName = ref(true);

const submitUser = () => {
    UserData.put(route('dashboard.user.update'), {
        onSuccess: () => {
            console.log('ok')
        }
    })
}

const submitUserImg = () => {
    UserImg.post(route('dashboard.user.update'))
}
const submitUserPsw = () => {
    UserPsw.put(route('dashboard.user.update'))
}
</script>
