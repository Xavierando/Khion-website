<template>
    <Listbox v-model="selectedItem">
        <ListboxButton>{{ selectedItem.name }}</ListboxButton>
        <ListboxOptions>
            <ListboxOption v-for="item in items.options" :key="item.name" :value="item.name">
                {{ item.name }} <div class="text-gray-300 text-sm pl-2">price change: {{ item.price }}</div>
            </ListboxOption>
        </ListboxOptions>
    </Listbox>
</template>

<script setup lang="ts">
import { ref, HTMLAttributes } from 'vue'
import {
    Listbox,
    ListboxButton,
    ListboxOptions,
    ListboxOption,
} from '@headlessui/vue'
import { useVModel } from '@vueuse/core'

const props = defineProps<{
    defaultValue?: string | number
    modelValue?: string | number
    class?: HTMLAttributes['class']
    items: Array<Object>
}>()


const emits = defineEmits<{
    (e: 'update:modelValue', payload: string | number): void
}>()

const modelValue = useVModel(props, 'modelValue', emits, {
    passive: true,
    defaultValue: props.defaultValue,
})

const selectedItem = ref(props.items.options[0])
</script>