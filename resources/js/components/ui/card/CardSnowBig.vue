<template>
  <Transition appear>
    <section>
      <Dialog v-model="ConfermaCarrello" />
      <div class="lg:max-w-6xl p-5 mx-auto bg-white/20 rounded-lg text-black">
        <h2 class="text-4xl font-semibold tracking-tight text-gray-900 sm:text-5xl text-center pb-10">{{
          product.name }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <img @click="onShow()" class="w-full h-full" :src="product.images[0]?.src" />
            <vue-easy-lightbox :visible="visibleRef" :imgs="imgsRef" :index="indexRef"
              @hide="onHide"></vue-easy-lightbox>
          </div>
          <div>
            <div>{{ product.description }}</div>
            <div class="flex flex-row-reverse  items-center justify-between">
              <div class="flex flex-row-reverse  items-center ">
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
              <div class="flex flex-row-reverse  items-center gap-4 ml-3 mt-6">
                <PlusCircleIcon :class="['h-8 cursor-pointer', { 'opacity-50': product.quantity === 0 }]"
                  @click="addquantity()" />
                <div>{{ quantity}}</div>
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
import { Cart_item, Product } from '@/types';
import { provide, ref } from 'vue';
import { MinusCircleIcon, PlusCircleIcon } from '@heroicons/vue/16/solid';
import { TrashIcon } from '@heroicons/vue/24/outline';
import VueEasyLightbox from 'vue-easy-lightbox';
import { useForm } from '@inertiajs/vue3';
import Dialog from '@/components/Dialog.vue';

const props = defineProps<{
  class?: HTMLAttributes['class'],
  product: Product,
  scenario: string,
  item?: Cart_item,
}>()

const visibleRef = ref(false)
const indexRef = ref(0) // default 0
//console.log(props.product.images.map((v) => v.src));
const imgsRef = ref(props.product.images.map((v) => v.src))
//const imgsRef = ref([])

const onShow = () => {
  visibleRef.value = true
}

const onHide = () => (visibleRef.value = false)

const quantity = ref();
if (props.item) {
  quantity.value = props.item?.quantity;
} else {
  quantity.value = (props.product.quantity > 0) ? 1 : 0;
}

const addquantity = () => {
  if (props.product.quantity === 0) {
    quantity.value = props.item?.quantity ?? 0;
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

const CartItem = useForm({
  'product': props.product.id,
  'quantity': 0
});

const addToCart = () => {
  ConfermaCarrello.value = true;
  CartItem.quantity = quantity.value;
  CartItem.post(route('cart.create'), { /*preserveState: true*/ });
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
  formUpdate.product = id;
  formUpdate.delete(route('cart.delete'), {

  });
}


</script>