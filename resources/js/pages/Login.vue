<script setup lang="ts">
import PageSection from '@/components/ui/section/PageSection.vue';
import { useAuthStore } from '@/stores/auth';
import { ref, watch } from 'vue';
import { useRouter } from 'vue-router';

const auth = useAuthStore();
const router = useRouter();
const email = ref('');
const password = ref('');

const redirect = () => {
  if (auth.loggedIn) {
    router.push({ path: '/' });
  }
};

auth.refresh().then(redirect);
</script>

<template>
  <PageSection class="h-screen align-center">
    <v-card max-width="500" class="mx-auto pa-6" color="secondary">
      <v-sheet class="d-flex justify-center bg-transparent pb-6">
        <v-avatar
          image="/images/logo.png"
          size="120"
          class="mx-auto"
        ></v-avatar>
      </v-sheet>
      <v-form @submit.prevent="auth.login(email, password).then(redirect)">
        <v-text-field
          v-model="email"
          label="Email address"
          type="email"
          :rules="auth.errors?.email"
        ></v-text-field>
        <v-text-field
          v-model="password"
          label="Password"
          type="password"
          :rules="auth.errors?.password"
        ></v-text-field>

        <v-btn
          color="primary"
          class="mt-2 pa-2 ml-auto mr-auto"
          type="submit"
          block
          variant="elevated"
          size="large"
          :loading="auth.loggingIn"
          >Submit</v-btn
        >
      </v-form>
    </v-card>
  </PageSection>
</template>
