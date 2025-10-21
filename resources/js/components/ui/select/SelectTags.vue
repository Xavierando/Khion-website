<template>
  <v-sheet class="d-flex">
    <v-select
      v-if="prodotto"
      v-model="tagsListValues"
      :items="tagsNameList"
      label="Chips"
      chips
      multiple
    >
      <template v-slot:append-item>
        <v-divider class="mt-2"></v-divider>
        <v-list-item title="Aggiungi nuovo Tag" @click="dialog = true">
        </v-list-item>
      </template>
    </v-select>
    <v-dialog v-model="dialog" max-width="400" persistent>
      <v-card prepend-icon="mdi-tag" title="Aggiungi un nuovo tag">
        <v-card-text>
          <v-text-field
            v-model="inputTag"
            :rules="[rules.nonVuoto, rules.Unico]"
          ></v-text-field>
        </v-card-text>
        <template v-slot:actions>
          <v-spacer></v-spacer>

          <v-btn @click="handleAnnullaAggiuntaTag"> Annulla </v-btn>

          <v-btn @click="handleAggiungiTag"> Aggiungi </v-btn>
        </template>
      </v-card>
    </v-dialog>
  </v-sheet>
</template>

<script lang="ts" setup>
import { useProductsStore } from '@/stores/products';
import { computed, ref, watch } from 'vue';
import { useTagStore } from '@/stores/tag';

const props = defineProps<{
  id: string;
}>();

const tags = useTagStore();
const prodottiStore = useProductsStore();
const tagsListValues = ref<string[]>([]);
const tagsNameList = ref<string[]>([]);
const prodotto = computed(() => prodottiStore.findProduct(Number(props.id)));

const dialog = ref(false);
const inputTag = ref('');
const inputTagHint = ref('');

tags.fetchAll().then((data) => {
  tagsNameList.value = tags.all.map((tag) => tag.tag);
});
prodottiStore.fetchProduct(props.id).then((data) => {
  const prodotto = prodottiStore.findProduct(Number(props.id));
  tagsListValues.value = prodotto ? prodotto.tags.map((tag) => tag.tag) : [];
});

watch(tagsListValues, (newValue, oldValue) => {
  if (!prodotto.value) return;
  if (newValue.length > oldValue.length) {
    const nameTagToAdd = newValue.find(
      (newTag) => !oldValue.some((oldTag) => oldTag === newTag)
    );
    const tagToAdd = nameTagToAdd ? tags.findByName(nameTagToAdd) : false;
    if (tagToAdd) {
      prodotto.value.addTag(tagToAdd);
    }
  }

  if (newValue.length < oldValue.length) {
    const nameTagToRemove = oldValue.find(
      (oldTag) => !newValue.some((newTag) => oldTag === newTag)
    );
    const tagToRemove = nameTagToRemove
      ? tags.findByName(nameTagToRemove)
      : false;
    if (tagToRemove) {
      prodotto.value.removeTag(tagToRemove);
    }
  }
});

const handleAnnullaAggiuntaTag = () => {
  inputTag.value = '';
  dialog.value = false;
};
const handleAggiungiTag = () => {
  if (
    rules.nonVuoto(inputTag.value) === true &&
    rules.Unico(inputTag.value) === true
  ) {
    tags.create(inputTag.value).then(() => {
      tagsListValues.value.push(inputTag.value);
      tagsNameList.value.push(inputTag.value);
    });

    dialog.value = false;
  }
};

const rules = {
  nonVuoto: (value: string) => !!value || 'Il campo non puo essere vuoto',
  Unico: (value: string) => {
    return !tags.findByName(value) || 'un TAG con questo nome esiste gia';
  },
};
</script>
