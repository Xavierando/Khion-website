<template>
    <v-layout>
        <v-navigation-drawer v-model="drawer" color="primary" disable-resize-watcher>
            <v-list nav>
                <v-list-item v-for="(item, i) in items" :key="i" :active="($route.path === item.path)" link :title="item.text"
                    @click="router.push({ path: item.path })" />
            </v-list>
        </v-navigation-drawer>

        <v-app-bar class="px-md-4">
            <template #prepend>
                <v-app-bar-nav-icon v-if="$vuetify.display.smAndDown" @click="drawer = !drawer" />
            </template>

            <v-img class="me-sm-8" max-width="40" src="/images/logo.png" />

            <template v-if="$vuetify.display.mdAndUp">
                <v-btn v-for="(item, i) in items" :key="i" :active="($route.path === item.path)" class="me-2 text-none" slim v-bind="item"
                    @click="router.push({ path: item.path })" />
            </template>

            <v-spacer />

            <template #append>

                <CartNav></CartNav>

                <v-btn v-if="auth.loggedIn" class="ms-1" icon>
                    <v-avatar :image="user.url" />

                    <v-menu activator="parent" origin="top">
                        <v-list>
                            <v-list-item link title="Update profile"  @click="router.push({ path: '/profilo' })" />
                            <v-list-item link title="Gestisci i Prodotti"  @click="router.push({ name: 'gestisciProdotti' })" />

                            <v-list-item link title="Sign out" @click="auth.logout()" />
                        </v-list>
                    </v-menu>
                </v-btn>

                <v-btn v-if="!auth.loggedIn" class="ms-1" icon="mdi-login" @click="router.push({ path: '/login' })">
                </v-btn>
            </template>
        </v-app-bar>
    </v-layout>
</template>

<script setup lang="ts">
import { shallowRef } from 'vue'
import { useAuthStore } from '@/stores/auth';
import { useRouter} from 'vue-router';
import CartNav from '../button/CartNav.vue';

const router = useRouter();
const auth = useAuthStore();
const user = auth.user;

const drawer = shallowRef(false)

const items = [
    {
        text: 'Home',
        path: '/'
    },
    {
        text: 'Prodotti',
        path: '/prodotti'
    }
]
</script>