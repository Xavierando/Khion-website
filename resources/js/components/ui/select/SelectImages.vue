<template>
  <v-sheet>
    <v-item-group v-model="selection" v-if="prodotto" mandatory>
      <v-row>
        <v-col
          v-for="(image, i) in prodotto.images"
          :key="i"
          cols="6"
          sm="3"
          md="2"
        >
          <v-item v-slot="{ isSelected, toggle }">
            <v-img :src="image.src" class="text-right pa-2" height="150">
              <v-btn
                @click="toggle"
                :icon="isSelected ? 'mdi-heart' : 'mdi-heart-outline'"
              ></v-btn>
            </v-img>
          </v-item>
        </v-col>
        <v-col cols="6" sm="3" md="2">
          <v-file-upload
            v-model="inputImage"
            density="compact"
            class="bg-transparent fill-height"
            browse-text=""
            divider-text=""
            title=""
            @change="handleChangePicture"
          ></v-file-upload>
        </v-col>
      </v-row>
    </v-item-group>
  </v-sheet>
</template>

<script lang="ts" setup>
import { useProductsStore } from '@/stores/products';
import { Image } from '@/types';
import { computed, ref, watch } from 'vue';

const props = defineProps<{
  id: string;
}>();

const prodottiStore = useProductsStore();
const selection = ref(0);
const prodotto = computed(() => prodottiStore.findProduct(Number(props.id)));
const inputImage = ref();

if (prodotto.value) {
  for (const imageIndex in prodotto.value.images) {
    if (
      prodotto.value.images[imageIndex].src ===
      prodotto.value.default_images.src
    ) {
      selection.value = Number(imageIndex);
    }
  }
}
watch(selection, () => {
  if (prodotto.value) {
    prodotto.value.setImageAsDefault(
      prodotto.value.images[selection.value].src
    );
  }
});
const handleChangePicture = () => {
  if (prodotto.value) {
    prodotto.value.addImage(inputImage.value);
  }
};
const dialog = ref(false);
</script>
