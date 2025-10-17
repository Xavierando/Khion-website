<script setup lang="ts">
import NavBar from './components/ui/nav/NavBar.vue';


</script>

<template>
    <v-sheet class="h-screen tw:bg-[url(@images/homepage.jpg)]  tw:bg-cover bg">
        <NavBar />
        <router-view v-slot="{ Component, route }">
            <div :key="route.name">
                <Component :is="Component" />
            </div>
        </router-view>
    </v-sheet>
</template>