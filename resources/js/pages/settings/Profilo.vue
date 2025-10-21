<template>
  <PageSection class="mt-16">
    <v-form v-if="auth.loggedIn">
      <v-img
        :src="user.url"
        rounded="circle"
        aspect-ratio="1/1"
        cover
        :width="130"
        :height="130"
        class="mx-auto"
      >
        <v-file-upload
          v-model="inputAvatar"
          density="compact"
          class="bg-transparent"
          browse-text=""
          divider-text=""
          title=""
          @change="handleChangePicture"
        ></v-file-upload>
      </v-img>
      <v-text-field
        v-model="user.name"
        label="Nome"
        @focusout="user.update('name')"
      />
      <v-text-field
        v-if="user.isTeam"
        v-model="user.role"
        label="role"
        @focusout="user.update('role')"
      />
      <v-textarea
        v-if="user.isTeam"
        label="bio"
        v-model="user.bio"
        @focusout="user.update('bio')"
      ></v-textarea>

      <v-btn color="error" @click="showCambiaPassword = !showCambiaPassword"
        >Cambia Password</v-btn
      >

      <v-expand-transition>
        <v-card v-if="showCambiaPassword" class="mt-3">
          <v-text-field v-model="oldPassword" label="Password" />
          <v-text-field
            v-model="newPassword"
            label="Ripeti Password"
            color="warning"
          />
          <v-card-actions class="justify-end">
            <v-btn color="error" @click="handleChangePassword">Submit</v-btn>
          </v-card-actions>
        </v-card>
      </v-expand-transition>
    </v-form>
  </PageSection>
</template>

<script setup lang="ts">
import PageSection from '@/components/ui/section/PageSection.vue';
import { useAuthStore } from '@/stores/auth';
import { ref, watch } from 'vue';
import { useRouter } from 'vue-router';

const auth = useAuthStore();
const user = auth.user;
const showCambiaPassword = ref(false);
const oldPassword = ref('');
const newPassword = ref('');
const inputAvatar = ref();

const redirect = () => {
  console.log('ciao');
  if (!auth.loggedIn) {
    const router = useRouter();
    router.push({ path: '/' });
  }
};

auth.refresh().then(redirect);

const handleChangePassword = () => {
  user.update('password', {
    oldPassword: oldPassword.value,
    newPassword: newPassword.value,
  });
};
const handleChangePicture = () => {
  console.log(inputAvatar.value);
  user.update('pic', { pic: inputAvatar.value });
};
</script>
