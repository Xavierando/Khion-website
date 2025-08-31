<template>
  <Transition appear>
    <section>
      <Dialog v-model="ConfermaCarrello" />
      <div class="lg:max-w-6xl px-5 pb-5 mx-auto bg-white/20 rounded-lg text-black">
        <h2 class="text-4xl font-semibold tracking-tight text-gray-900 sm:text-5xl text-center pb-10">{{
          product.name }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="grid grid-cols-3 items-center">
            <div class="col-start-1 col-end-4">
              <img @click="onShow(0)" class="w-full" :src="product.default_images?.src" />
              <vue-easy-lightbox :visible="visibleRef" :imgs="imgsRef" :index="indexRef"
                @hide="onHide"></vue-easy-lightbox>
            </div>
            <div v-for="(img, index) in imgGallery" :key="img.id">
              <img @click="onShow(index + 1)" class="w-full" :src="img.src" />
            </div>
          </div>
          <div>
            <div class="grow mb-4 flex flex-row flex-wrap justify-between gap-2 items-start">
              <button class="text-gray-600 text-sm bg-blue-200 rounded-sm p-1 px-2" v-for="tag in product.tags"
                :key="tag.id">{{ tag.tag }}</button>
            </div>
            <div>{{ product.description }}</div>
            <div class="flex flex-row-reverse  items-center justify-between">
              <div class="flex flex-row-reverse  items-center  my-6">
                <button v-if="!item"
                  class="cursor-pointer bg-blue-500 hover:bg-blue-300 text-white font-bold py-2 m-4 px-4 w-fit rounded"
                  @click="addToCart()">
                  Acquista
                </button>
                <button v-if="item" class="bg-red-600/50 hover:bg-red-600 p-1 rounded-full ml-2 cursor-pointer"
                  @click="DeleteCartItem(product.id)">
                  <TrashIcon class="size-6" aria-hidden="true" />
                </button>
                <div class="text-lg text-black flex">
                  <div>{{ quantity * product.base_price }}</div> &euro;
                </div>
              </div>
              <div class="flex flex-row-reverse  items-center gap-4 ml-3">
                <PlusCircleIcon :class="['h-8 cursor-pointer', { 'opacity-50': quantityLeft === 0 }]"
                  @click="addquantity()" />
                <div>{{ quantity }}</div>
                <MinusCircleIcon :class="['h-8 cursor-pointer', { 'opacity-50': quantity === 0 }]"
                  @click="subquantity()" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </Transition>
</template>

<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { CartItem, Product } from '@/types';
import { computed, provide, ref } from 'vue';
import { MinusCircleIcon, PlusCircleIcon } from '@heroicons/vue/16/solid';
import { TrashIcon } from '@heroicons/vue/24/outline';
import VueEasyLightbox from 'vue-easy-lightbox';
import { useForm } from '@inertiajs/vue3';
import Dialog from '@/components/Dialog.vue';

const props = defineProps<{
  class?: HTMLAttributes['class'],
  product: Product,
  scenario: string,
  item?: CartItem,
}>()

const visibleRef = ref(false);
const indexRef = ref(0); // default 0


const imgDefault = ref(props.product.images.find((v: {
  src: string;
}) => v.src === props.product.default_images.src));

const imgCarousel = computed(() => [
  imgDefault.value,
  ...props.product.images.filter((v: {
    src: string;
  }) => v.src !== props.product.default_images.src)
]);

const imgsRef = ref(imgCarousel.value.map((v: {
  src: string;
}) => v.src));

const imgGallery = computed(() => [
  ...imgCarousel.value.slice(1)
]);

const onShow = (index: number) => {
  indexRef.value = index
  visibleRef.value = true
}

const onHide = () => (visibleRef.value = false)

const quantity = ref();
const quantityLeft = computed(() => (props.item) ? props.product.quantity : props.product.quantity - quantity.value)
if (props.item) {
  quantity.value = props.item?.quantity;
} else {
  quantity.value = (props.product.quantity > 0) ? 1 : 0;
}

const addquantity = () => {
  if (quantityLeft.value === 0) {
    quantity.value = props.item?.quantity ?? props.product.quantity;
  } else {
    quantity.value++;
  }

  if (props.item) {
    submitUpdate(props.product.id, quantity.value)
  }
}

const subquantity = () => {
  if (quantity.value <= 0) {
    quantity.value = 0;
  } else {
    quantity.value--;
  }

  if (props.item) {
    submitUpdate(props.product.id, quantity.value)
  }
}

const FormCartItem = useForm({
  'product': props.product.id,
  'quantity': 0
});

const addToCart = () => {
  ConfermaCarrello.value = true;
  FormCartItem.quantity = quantity.value;
  FormCartItem.post(route('cart.create'), { /*preserveState: true*/ });
};

const ConfermaCarrello = ref(false)
provide('ConfermaCarrello', ConfermaCarrello)


const formUpdate = useForm({
  product: 0,
  quantity: 0
})

function submitUpdate(id: number, quantity: number) {
  formUpdate.product = id;
  formUpdate.quantity = quantity;
  formUpdate.put(route('cart.update'), {
    /*preserveState: true,*/
  });
}

function DeleteCartItem(id: number) {
  formUpdate.delete(route('cart.delete', { product: id }), {

  });
}


</script>