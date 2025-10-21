import { defineStore } from "pinia";

export const useSearchStore = defineStore("search", {
    state: () => {
        return { tearm: "", includeSoldOut: true } as {
            tearm: string;
            includeSoldOut: boolean;
        };
    },
});
