import { defineStore } from "pinia";
import axios from "axios";
import { Tag } from "@/types";

export const useTagStore = defineStore("tags", {
    state: () => {
        return {
            list: [],
        } as Tags;
    },
    actions: {
        async fetchAll() {
            try {
                const response = await axios.get("/api/tags");
                if (response.request.status === 200) {
                    if (response.data.message === "success") {
                        this.list = response.data.data.tags;
                    }
                }
            } catch (error) {
                // let the form component display the error
                return error;
            }
        },
        async create(tag: string) {
            //send update request
            const response = await axios.post("/api/tags", {
                tag,
            });
            if (response.request.status === 200) {
                if (response.data.message === "success") {
                    this.list.push(response.data.data);
                }
            }
        },
    },
    getters: {
        all: (state) => state.list, // .sort((tagA, tagB) => tagA.tag.charCodeAt(0) - tagB.tag.charCodeAt(0)),
        findByName: (state) => (tag: string) =>
            state.list.find((item) => item.tag === tag) ?? false,
        findById: (state) => (id: number) =>
            state.list.find((item) => item.id === id) ?? false,
    },
});

interface Tags {
    list: Tag[];
}
