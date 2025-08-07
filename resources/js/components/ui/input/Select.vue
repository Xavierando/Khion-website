<template>
    <Listbox v-model="selectedItem">
        <ListboxLabel class="mr-4 text-sm/6 font-medium text-gray-900 p-2">{{ props.name }}</ListboxLabel>
        <ListboxButton class="cursor-pointer group-hover:bg-blue-100  transition-bg duration-700">{{ selectedItem }}</ListboxButton>
        <ul class="absolute bg-white text-xl border-black border rounded-xl mt-6">
            <li v-for="item in items" :key="item.name" :value="item.name" class="flex flex-row cursor-pointer font-bold  hover:bg-blue-100 p-3  transition-bg duration-700">
                {{ item.name }} <div class="text-gray-500 font-normal pl-2">price change: {{ item.price }}</div>
            </li>
        </ul>
    </Listbox>
</template>

<script setup lang="ts">
import { ref, HTMLAttributes } from 'vue'
import {
    Listbox,
    ListboxButton,
    ListboxOptions,
    ListboxOption,
    ListboxLabel,
} from '@headlessui/vue'
import { useVModel } from '@vueuse/core'

const props = defineProps<{
    defaultValue?: string | number
    modelValue?: string | number
    class?: HTMLAttributes['class']
    items: Array<Object>
    name: string
}>()


const emits = defineEmits<{
    (e: 'update:modelValue', payload: string | number): void
}>()

const modelValue = useVModel(props, 'modelValue', emits, {
    passive: true,
    defaultValue: props.defaultValue,
})

const selectedItem = ref(props.items[0].name)
</script>