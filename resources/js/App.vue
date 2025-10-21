<script setup lang="ts">
import NavBar from "./components/ui/nav/NavBar.vue";
</script>

<template>
    <v-theme-provider>
        <v-sheet>
            <NavBar />
            <router-view v-slot="{ Component, route }">
                <div :key="route.name">
                    <Component :is="Component" />
                </div>
            </router-view>
        </v-sheet>
    </v-theme-provider>
</template>
