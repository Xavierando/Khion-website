<script setup lang="ts">
import AppLayout from '../index.vue';
import Input from '@/components/ui/input/Input.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { useDropZone } from '@vueuse/core';
import TextBox from '@/components/ui/input/TextBox.vue';
import { TrashIcon, PlusIcon } from '@heroicons/vue/24/outline';
import { Image, Product } from '@/types';

const props = defineProps<{
    status?: string,
    imgpath?: string,
    products?: Product[],
}>()

const productSorted = computed(() => props.products?.sort((a, b): number => {
    if ((a.quantity == 0 && b.quantity == 0) || (a.quantity > 0 && b.quantity > 0)) {
        return (a.created > b.created) ? -1 : 1;
    } else {
        return (a.quantity > 0) ? -1 : 1;
    }
}))

const page = usePage();
const altPic = ref();
const imageGallery = ref<Array<{
    file: File | null;
    src: string;
}>>([]);

const UserData = useForm({
    name: page.props.auth.user?.name,
    role: page.props.auth.user?.role,
    bio: page.props.auth.user?.bio,
})

const UserImg = useForm<{
    _method: string;
    pic: File | null;
}>({
    _method: 'put',
    pic: null,
})

const UserPsw = useForm({
    oldpsw: '',
    newpsw: '',
})

const dropZonePic = ref<HTMLDivElement>()

function onDrop(files: File[] | null) {
    console.log('up');
    if (files) {
        imageGallery.value.push({
            'file': files[0],
            'src': URL.createObjectURL(files[0]),
        });
        submitData('images');
    }
}

useDropZone(dropZonePic, {
    onDrop,
    dataTypes: ['image/jpeg', 'image/png'],
    preventDefaultForUnhandled: false,
})

const submitUser = () => {
    UserData.put(route('dashboard.user.update'), {
        onSuccess: () => {
            if (props.status === 'success') {
                formAddProduct.value = false;
                console.log('aok')
            }
            console.log('ok')
        }
    })
}

const submitUserImg = () => {
    UserImg.post(route('dashboard.user.update'))
}
const submitUserPsw = () => {
    UserPsw.put(route('dashboard.user.update'))
}

const inputSearch = ref('');
const formAddProduct = ref(false)

const PData = useForm<{
    _method: string,
    id: number | null;
    code: string;
    name: string;
    description: string;
    base_price: number;
    quantity: number;
    images: File[];
    deleteImage: string;
    update: string;
}>({
    _method: 'post',
    id: null,
    code: '',
    name: '',
    description: '',
    base_price: 0,
    quantity: 0,
    images: [],
    deleteImage: '',
    update: '',
})

function addProduct() {
    const imgGallery = imageGallery.value.map((v: {
        file: File | null;
        src: string;
    }) => { return v.file });

    PData.images = imgGallery.filter((v: File | null) => v instanceof File)

    PData.post(route('dashboard.products'), {
        onFinish: () => {
            if (props.status === 'success') {
                formAddProduct.value = false;
            }
        }
    })
}

const submitData = (up: string) => {
    if (PData.id !== null) {
        PData._method = 'put';
        PData.update = up;

        const imgGallery = imageGallery.value.map((v: {
            file: File | null;
            src: string;
        }) => { return v.file });

        PData.images = imgGallery.filter((v: File | null) => v instanceof File)

        PData.post(route('dashboard.products.update', { product: PData.id }), {
            onFinish: () => {
                PData.update = '';
                PData._method = 'post';

                if (props.imgpath) {
                    imageGallery.value.map((v: {
                        file: File | null;
                        src: string;
                    }) => {
                        if (v.file != null) {
                            return { file: null, src: props.imgpath }
                        }
                        return v;
                    });
                }
            }
        })
    }
}

function addImageByInput(e: Event) {
    if (e.target instanceof HTMLInputElement) {
        if (e.target.files?.item(0) instanceof File) {
            var file = e.target.files.item(0) as File;
            onDrop([file])
        }
    }
}

function modificaProdotto(product: Product) {
    PData.id = product.id;
    PData.code = product.code;
    PData.name = product.name;
    PData.description = product.description;
    PData.base_price = product.base_price;
    PData.quantity = product.base_quantity;
    imageGallery.value = [];
    product.images.map((v: Image) => imageGallery.value.push({
        'file': null,
        'src': v.src,
    })
    );
    formAddProduct.value = true;
}

function eliminaProdotto(product: Product) {
    useForm({}).delete(route('dashboard.products.delete', { product: product.id }));
}

function eliminaImmagine(src: string) {
    imageGallery.value = imageGallery.value.filter(($v: {
        file: File | null;
        src: string;
    }) => $v.src != src)

    console.log(imageGallery.value);

    if (PData.id !== null) {
        PData.deleteImage = src;
        submitData('image');
    }
}

</script>



<template>
    <AppLayout>
        <section id="personal"
            class="h-screen rounded-xl border border-sidebar-border/70 dark:border-sidebar-border pl-5 flex flex-col">
            <h2 class="mt-24 text-xl text-bold p-3">Prodotti</h2>
            <div v-if="!formAddProduct">
                <div class="flex flex-row">
                    <Input type="search" v-model="inputSearch" />
                    <div class="rounded-full bg-blue-300 w-12 h-12" @click="PData.id = null; formAddProduct = true">
                        <PlusIcon />
                    </div>
                </div>
                <div class="divide-y">
                    <div v-for="product in productSorted" class="flex flex-row py-2 gap-2">
                        <div class="grid grid-cols-2 w-fit">
                            <div>codice:</div>
                            <div>{{ product.code }}</div>
                            <div>nome:</div>
                            <div>{{ product.name }}</div>
                            <div class="pr-2">disponibilitá alla vendita:</div>
                            <div>{{ product.quantity }}</div>
                        </div>
                        <div class="flex flex-row py-2 gap-2">
                            <button class="p-2 rounded-sm bg-blue-500 h-fit cursor-pointer hover:bg-blue-600"
                                @click="modificaProdotto(product)">Modifica</button>
                            <button class="p-2 rounded-sm bg-red-400 h-fit cursor-pointer hover:bg-red-600"
                                @click="eliminaProdotto(product)">Elimina</button>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="formAddProduct" class="flex flex-col pr-3">
                <div class="flex flex-row justify-between">
                    <a :href="route('dashboard.products')"><button
                            class="p-2 rounded-sm bg-blue-500 h-fit w-fit cursor-pointer hover:bg-blue-600">Torna ai
                            Prodotti</button></a>
                    <button class="p-2 rounded-sm bg-blue-500 h-fit w-fit cursor-pointer hover:bg-blue-600"
                        @click="addProduct()">Crea Nuovo Prodotto</button>
                </div>
                <div class="flex flex-col grow-[10vw]">
                    <label>Code</label>
                    <Input class="max-w-sm mb-3" v-model="PData.code" @change="submitData('code')" />
                    <div class="text-red-700 text-sm" v-if="PData.errors.code">{{ PData.errors.code }}</div>

                    <label>Name</label>
                    <Input class="max-w-sm mb-3" v-model="PData.name" @change="submitData('name')" />
                    <div class="text-red-700 text-sm" v-if="PData.errors.name">{{ PData.errors.name }}</div>

                    <label>Description</label>
                    <TextBox class="max-w-sm mb-3 min-h-24" v-model="PData.description"
                        @change="submitData('description')" />
                    <div class="text-red-700 text-sm" v-if="PData.errors.description">{{ PData.errors.description }}
                    </div>

                    <label>Prezzo di vendita</label>
                    <Input type="number" class="max-w-sm mb-3" v-model="PData.base_price"
                        @change="submitData('base_price')" />
                    <div class="text-red-700 text-sm" v-if="PData.errors.base_price">{{ PData.errors.base_price }}</div>

                    <label>Quantita disponibile</label>
                    <Input type="number" class="max-w-sm mb-3" v-model="PData.quantity"
                        @change="submitData('quantity')" />
                    <div value="1" class="text-red-700 text-sm" v-if="PData.errors.quantity">{{ PData.errors.quantity }}
                    </div>
                </div>
                <div ref="dropZonePic"
                    class="w-fit h-fit shrink m-8 grid grid-cols-3 gap-2 items-center justify-items-center">
                    <div @click="altPic.click()">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                        </svg>
                        <input type="file" class="hidden" ref="altPic" @input="addImageByInput($event)" />
                    </div>

                    <div v-for="image in imageGallery" class="relative">
                        <img :src="image.src" class="min-h-24" />
                        <TrashIcon @click="eliminaImmagine(image.src)"
                            class="absolute bg-white/30 top-0 w-full h-full opacity-0 hover:opacity-100 transition-opacity duration-300" />
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>