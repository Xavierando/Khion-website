import { defineStore } from "pinia";
import axios from "axios";
import { Image, Product, Tag } from "@/types";
import dayjs from "dayjs";
import { useSearchStore } from "./search";

export const useProductsStore = defineStore("products", {
    state: () => {
        return {
            list: [],
        } as Products;
    },
    actions: {
        async fetchAllProducts() {
            try {
                const response = await axios.get("/api/products");
                if (response.request.status === 200) {
                    if (response.data.message === "success") {
                        this.list = [];
                        for (const key in response.data.data.products) {
                            this.list.push(
                                new Prodotto(
                                    response.data.data.products[key] as Product,
                                ),
                            );
                        }
                    }
                }
            } catch (error) {
                // let the form component display the error
                return error;
            }
        },
        async fetchProduct(id: string | number) {
            try {
                id = Number(id);
                const response = await axios.get(
                    "/api/products/" + id.toString(),
                );
                if (response.request.status === 200) {
                    if (response.data.message === "success") {
                        this.list = this.list.filter(
                            (prodotto) => prodotto.id !== id,
                        );
                        this.list.push(
                            new Prodotto(
                                response.data.data
                                    .product as unknown as Product,
                            ),
                        );
                    }
                }
            } catch (error) {
                // let the form component display the error
                return error;
            }
        },
        async createProduct() {
            try {
                const response = await axios.post("/api/products/", {});
                if (response.request.status === 200) {
                    if (response.data.message === "success") {
                        this.list.push(
                            new Prodotto(
                                response.data.data
                                    .product as unknown as Product,
                            ),
                        );
                        return Number(response.data.data.product.id);
                    }
                }
                return false;
            } catch (error) {
                // let the form component display the error
                console.log(error);
                return false;
            }
        },
    },
    getters: {
        hightlight: (state) =>
            state.list
                .filter((product) => product.quantity > 0)
                .sort(Prodotto.sortByAvailabilityAndDate)
                .slice(0, 4),
        all: (state) => state.list.sort(Prodotto.sortByAvailabilityAndDate),
        filtered: (state) => {
            const search = useSearchStore();

            return state.list
                .filter(
                    (prodotto) =>
                        (search.includeSoldOut || prodotto.quantity > 0) &&
                        (search.tearm.length === 0 ||
                            prodotto.name.includes(search.tearm) ||
                            prodotto.code.includes(search.tearm) ||
                            prodotto.description.includes(search.tearm) ||
                            prodotto.base_price
                                .toString()
                                .includes(search.tearm) ||
                            prodotto.tags.some((tag) =>
                                tag.tag.includes(search.tearm),
                            )),
                )
                .sort(Prodotto.sortByAvailabilityAndDate);
        },
        findProduct: (state) => (productId: number) =>
            state.list.find((product) => product.id === productId) ?? false,
    },
});

interface Products {
    list: Prodotto[];
}

export interface IProdotto {
    id: number;
    name: string;
    code: string;
    description: string;
    base_price: number;
    quantity: number;
    base_quantity: number;
    images: ImmagineProdotto[];
    default_images: Image;
    tags: Tag[];
    created: string;
    addTag(Tag: Tag): void;
    removeTag(Tag: Tag): void;
    addImage(File: File): void;
    removeImage(src: string): void;
    setImageAsDefault(src: string): void;
    update(): Promise<boolean>;
}

export class Prodotto implements IProdotto {
    readonly id;
    name = "";
    code = "";
    description = "";
    base_price = 0;
    quantity = 0;
    base_quantity = 0;
    images = [] as ImmagineProdotto[];
    default_images = {} as Image;
    tags = [] as Tag[];
    created = "";

    constructor(data: Product) {
        this.id = data.id;
        this.name = data.name;
        this.code = data.code;
        this.description = data.description;
        this.base_price = data.base_price;
        this.quantity = data.quantity;
        this.base_quantity = data.base_quantity;
        this.setImages(data.images);
        if (data.default_images) {
            const immagineProdotto = new ImmagineProdotto(data.default_images);
            immagineProdotto.productId = this.id;
            this.default_images = immagineProdotto;
        }
        this.tags = data.tags;
        this.created = data.created;
    }
    setImages(images: Image[]): void {
        this.images = [];
        for (const image in images) {
            const immagineProdotto = new ImmagineProdotto(images[image]);
            immagineProdotto.productId = this.id;
            this.images.push(immagineProdotto);
        }
    }
    addTag(Tag: Tag): void {
        if (!this.tags.some((tag: Tag) => tag.id === Tag.id)) {
            this.tags.push(Tag);
        }
    }
    removeTag(Tag: Tag): void {
        this.tags = this.tags.filter((tag: Tag) => tag.id !== Tag.id);
    }
    addImage(File: File): void {
        const newImage = new ImmagineProdotto({
            id: "new",
            thumbnail: URL.createObjectURL(File),
            src: URL.createObjectURL(File),
            caption: "",
        });
        newImage.productId = this.id;
        newImage.blob = File;
        this.images.push(newImage);
    }
    removeImage(src: string): void {
        this.images = this.images.filter((image: Image) => image.src !== src);
    }
    setImageAsDefault(src: string): void {
        for (const imageIndex in this.images) {
            if (this.images[imageIndex].src === src) {
                this.default_images = this.images[Number(imageIndex)];
            }
        }
    }
    async update(): Promise<boolean> {
        const response = await axios.put(
            "/api/products/" + this.id.toString(),
            {
                code: this.code,
                name: this.name,
                description: this.description,
                base_price: this.base_price,
                quantity: this.base_quantity,
                tags: this.tags,
            },
        );
        if (response.request.status === 200) {
            if (response.data.message === "success") {
                this.images.forEach(async (image) => {
                    await image.sync();
                    if (image.src === this.default_images.src) {
                        await image.setAsDefault();
                    }
                });
                return true;
            }
        }

        return false;
    }
    static sortByAvailabilityAndDate(
        ProdottoA: Prodotto,
        ProdottoB: Prodotto,
    ): number {
        if (ProdottoA.quantity > 0 === ProdottoB.quantity > 0) {
            return (
                dayjs(ProdottoB.created).unix() -
                dayjs(ProdottoA.created).unix()
            );
        }
        if (ProdottoA.quantity > 0) {
            return -1;
        } else {
            return 1;
        }
    }
}

export interface IImmagineProdotto {
    id: number | string;
    productId: number;
    thumbnail: string;
    src: string;
    caption: string;
    blob?: File;
    deletable?: boolean;
    sync(): Promise<boolean>;
    create(): Promise<boolean>;
    setAsDefault(): Promise<boolean>;
    delete(): Promise<boolean>;
}

export class ImmagineProdotto implements IImmagineProdotto {
    id: number | string;
    productId = 0;
    thumbnail = "";
    src = "";
    caption = "";
    blob?: File;
    deletable?: boolean;
    constructor(data: Image) {
        this.id = data.id;
        this.thumbnail = data.thumbnail;
        this.src = data.src;
        this.caption = data.caption;
        this.blob = undefined;
        this.deletable = false;
    }
    async sync(): Promise<boolean> {
        if (this.deletable) {
            return this.delete();
        }
        if (this.id === "new") {
            return this.create();
        }
        return false;
    }
    async setAsDefault(): Promise<boolean> {
        const response = await axios.put("/api/gallery/" + this.id.toString());
        if (response.request.status === 200) {
            if (response.data.message === "success") {
                return true;
            }
        }
        return true;
    }
    async delete(): Promise<boolean> {
        const response = await axios.delete(
            "/api/gallery/" + this.id.toString(),
        );
        if (response.request.status === 200) {
            if (response.data.message === "success") {
                return true;
            }
        }
        return true;
    }
    async create(): Promise<boolean> {
        if (this.blob) {
            const response = await axios.postForm(
                "/api/products/" + this.productId.toString() + "/gallery",
                {
                    file: this.blob,
                },
            );
            if (response.request.status === 200) {
                if (response.data.message === "success") {
                    this.id = Number(response.data.data.id);
                    this.blob = undefined;
                    return true;
                }
            }
        }
        return false;
    }
}
