<template>
    <!--
    This example requires updating your template:

    ```
    <html class="h-full bg-gray-100">
    <body class="h-full">
    ```
  -->
    <header class="">

        <Transition appear>
            <Disclosure as="nav" class="bg-gray-800" v-slot="{ open }">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 items-center justify-between">
                        <div class="flex items-center">
                            <div class="shrink-0">
                                <img class="size-8" src="@images/logo.png" alt="Your Company" />
                            </div>
                            <div class="hidden md:block">
                                <div class="ml-10 flex items-baseline space-x-4">
                                    <a v-for="item in visibleNavigation" :key="item.name" :href="item.href"
                                        :class="[item.current ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white', 'rounded-md px-3 py-2 text-sm font-medium']"
                                        :aria-current="item.current ? 'page' : undefined">{{ item.name }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="hidden md:block">
                            <div class="ml-4 flex items-center md:ml-6">
                                <button type="button"
                                    class="relative rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800 focus:outline-hidden">
                                    <a href="/cart">
                                        <span class="absolute -inset-1.5" />
                                        <span class="sr-only">Carrello</span>
                                        <span class="absolute right-0 top-0 bg-red-500 rounded-full text-sm px-1 py-0 m-0 text-white " v-if="(CartLength > 0)" >{{ CartLength }}</span>
                                        <ShoppingCartIcon class="size-6" aria-hidden="true" />
                                    </a>
                                </button>

                                <div class="ml-10 flex items-baseline space-x-4" v-if="!page.props.auth.user">
                                    <a :href="route('login')"
                                        :class="['text-gray-300 hover:bg-gray-700 hover:text-white', 'rounded-md px-3 py-2 text-sm font-medium']">{{
                                            'Login' }}</a>
                                </div>

                                <!-- Profile dropdown -->
                                <Menu as="div" class="relative ml-3">
                                    <MenuButton v-if="user"
                                        class="relative flex max-w-xs items-center rounded-full bg-gray-800 text-sm focus:outline-hidden focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-gray-800">
                                        <span class="absolute -inset-1.5" />
                                        <span class="sr-only">Open user menu</span>
                                        <img class="size-8 rounded-full" :src="'/images/' + user?.imageUrl" alt="" />
                                    </MenuButton>

                                    <transition enter-active-class="transition ease-out duration-100"
                                        enter-from-class="transform opacity-0 scale-95"
                                        enter-to-class="transform opacity-100 scale-100"
                                        leave-active-class="transition ease-in duration-75"
                                        leave-from-class="transform opacity-100 scale-100"
                                        leave-to-class="transform opacity-0 scale-95">
                                        <MenuItems
                                            class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black/5 focus:outline-hidden">
                                            <MenuItem v-for="item in userNavigation" :key="item.name"
                                                v-slot="{ active }" @click=item.event>
                                            <a :href="item.href"
                                                :class="[active ? 'bg-gray-100 outline-hidden' : '', 'block px-4 py-2 text-sm text-gray-700']">{{
                                                    item.name }}</a>
                                            </MenuItem>
                                        </MenuItems>
                                    </transition>
                                </Menu>
                            </div>
                        </div>
                        <div class="-mr-2 flex md:hidden">
                            <!-- Mobile menu button -->
                            <DisclosureButton
                                class="relative inline-flex items-center justify-center rounded-md bg-gray-800 p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800 focus:outline-hidden">
                                <span class="absolute -inset-0.5" />
                                <span class="sr-only">Open main menu</span>
                                <Bars3Icon v-if="!open" class="block size-6" aria-hidden="true" />
                                <XMarkIcon v-else class="block size-6" aria-hidden="true" />
                            </DisclosureButton>
                        </div>
                    </div>
                </div>

                <DisclosurePanel class="md:hidden">
                    <div class="space-y-1 px-2 pt-2 pb-3 sm:px-3">
                        <DisclosureButton v-for="item in visibleNavigation" :key="item.name" as="a" :href="item.href"
                            :class="[item.current ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white', 'block rounded-md px-3 py-2 text-base font-medium']"
                            :aria-current="item.current ? 'page' : undefined">{{ item.name }}</DisclosureButton>
                    </div>
                    <div class="border-t border-gray-700 pt-4 pb-3">
                        <div class="flex items-center px-5" v-if="user">
                            <div class="shrink-0">
                                <img class="size-10 rounded-full" :src="'/images/' + user?.imageUrl" alt="" />
                            </div>
                            <div class="ml-3">
                                <div class="text-base/5 font-medium text-white">{{ user?.name }}</div>
                                <div class="text-sm font-medium text-gray-400">{{ user?.email }}</div>
                            </div>
                            <button type="button"
                                class="relative ml-auto shrink-0 rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800 focus:outline-hidden">
                                <a href="/cart">
                                    <span class="absolute -inset-1.5" />
                                    <span class="sr-only">View notifications</span>
                                    <ShoppingCartIcon class="size-6" aria-hidden="true" />
                                </a>
                            </button>
                        </div>
                        <div class="mt-3 space-y-1 px-2">
                            <DisclosureButton v-for="item in userSmallNavigation" :key="item.name" as="a" :href="item.href"
                                @click=item.event
                                class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white">
                                {{ item.name }}</DisclosureButton>
                        </div>
                    </div>
                </DisclosurePanel>
            </Disclosure>
        </Transition>
    </header>
</template>

<script setup lang="ts">
import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import { Bars3Icon, ShoppingCartIcon, XMarkIcon } from '@heroicons/vue/24/solid';
import { computed, inject, reactive } from 'vue';

import { usePage, useForm } from '@inertiajs/vue3';
const page = usePage();
const form = useForm({

});

const submit = () => {
    form.post(route('logout'), { onFinish: () => { } });
};

const user = computed(() => page.props.auth.user);


const navigation = reactive([
    { name: 'Home', href: route('home'), current: false, visible: true },
    { name: 'Shop', href: route('products.index'), current: false, visible: true },
    //{ name: 'Articles', href: '#', current: false, visible: true },
    //{ name: 'Reports', href: '#', current: false, visible: computed(() => page.props.auth.user?.isTeam ?? false) }
])

const visibleNavigation = computed(() => navigation.filter((i) => i.visible))

const BaseUserNavigation = reactive([
    { name: 'Settings', href: route('dashboard'), event: '', visible: computed(() => page.props.auth.user ?? false) },
    { name: 'Sign out', href: '#', event: submit, visible: computed(() => page.props.auth.user ?? false) },
    { name: 'Login', href: '/login', event: '', visible: computed(() => !page.props.auth.user) }
])

const userNavigation = computed(() => BaseUserNavigation.filter((i) => i.visible))

const BaseUserSmallNavigation = reactive([
    { name: 'User Settings', href: route('dashboard'), event: '', visible: computed(() => page.props.auth.user ?? false) },
    { name: 'Orders', href: route('dashboard.order'), event: '', visible: computed(() => page.props.auth.user ?? false) },
    { name: 'Sign out', href: '#', event: submit, visible: computed(() => page.props.auth.user ?? false) },
    { name: 'Login', href: '/login', event: '', visible: computed(() => !page.props.auth.user) }
])

const userSmallNavigation = computed(() => BaseUserSmallNavigation.filter((i) => i.visible))

const CartLength = computed(()=>page.props.auth.cartItems);

</script>