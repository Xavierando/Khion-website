<script setup lang="ts">
import { type BreadcrumbItem } from '@/types';
import AppLayout from '../index.vue';
import Input from '@/components/ui/input/Input.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useDropZone } from '@vueuse/core';
import TextBox from '@/components/ui/input/TextBox.vue';

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

const UserImg = useForm({
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



<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <section id="personal"
            class="h-screen rounded-xl border border-sidebar-border/70 dark:border-sidebar-border pl-5 flex flex-col">
            <h2 class="mt-24 text-xl text-bold p-3">Dati personali</h2>
            <!--<div class="grid grid-cols-1 md:grid-cols-2 content-center justify-between">-->
            <div class="flex flex-col md:flex-row">
                <div class="flex flex-col grow-[10vw]">
                    <label>Nome</label>
                    <div class="flex flex-row">
                        <Input class="max-w-sm mb-3" v-model="UserData.name" @change="submitUser()" />
                        <div class=" bg-green-700 rounded-full p-1 h-fit w-fit" :class="{ hidden: successName }"><svg
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                        </div>
                    </div>
                    <div class="text-red-700 text-sm" v-if="UserData.errors.name">{{ UserData.errors.name }}</div>


                    <label>Ruolo</label>
                    <Input class="max-w-sm mb-3" v-model="UserData.role" @change="submitUser()" />
                    <div v-if="UserData.errors.role">{{ UserData.errors.role }}</div>
                </div>
                <div ref="dropZonePic" class="w-fit h-fit shrink m-8">
                    <div class="relative border-dotted h-24 w-24 rounded-full border-dashed border-2 border-blue-700 flex justify-center items-center bg-cover cursor-pointer"
                        :style="`background-image: url(/images/${page.props.auth.user?.imageUrl})`"
                        @click="altPic.click()">
                        <span class="block text-gray-400 font-normal"><svg xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                            </svg>
                        </span>
                        <input type="file" class="hidden" ref="altPic"
                            @onChange="UserImg.pic = $event.target.files[0];submitUserImg();" />
                    </div>
                </div>
            </div>

            <label>Bio</label>
            <TextBox class="max-w-sm mb-3 min-h-24" v-model="UserData.bio" @change="submitUser()" />
            <div v-if="UserData.errors.bio">{{ UserData.errors.bio }}</div>

            <div>
                <label>Vecchia Password</label>
                <Input class="max-w-sm mb-3" v-model="UserPsw.oldpsw" />


                <label>Nuova Password</label>
                <Input class="max-w-sm mb-3" v-model="UserPsw.newpsw" />

                <div class="align-right flex max-w-sm">
                    <button class="bg-red-500 p-2 rounded-lg ml-auto text-white cursor-pointer"
                        @click="submitUserPsw()">Cambia
                        password</button>
                </div>
            </div>
        </section>
    </AppLayout>
</template>