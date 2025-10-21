<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import { cn } from '@/lib/utils';
import { Product } from '@/types';
import { ref } from 'vue';
import { teamsMember } from '@/stores/teams';

const expand = ref(false);

const props = defineProps<{
  class?: HTMLAttributes['class'];
  user: teamsMember;
}>();
</script>

<template>
  <v-card max-width="344" class="mx-auto">
    <v-container>
      <v-row justify="center" no-gutters>
        <v-col cols="12" align="center">
          <v-avatar
            class="mx-auto center"
            color="surface-light"
            :image="user.url"
            :size="250"
          />
        </v-col>
      </v-row>
    </v-container>
    <v-card-title class="text-h4 font-weight-bold">
      {{ user.name }}
    </v-card-title>
    <v-card-subtitle class="font-weight-bold">
      {{ user.role }}
    </v-card-subtitle>
    <v-card-text class="text-body-1">
      Lorem ipsum is a dummy or placeholder text commonly used in graphic
      design, publishing, and web development. Its purpose is to permit a page
      layout to be designed, independently of the copy that will subsequently
      populate it, or to demonstrate various fonts of a typeface without
      meaningful text that could be distracting.
      {{ user.bio }}
    </v-card-text>
  </v-card>
</template>
