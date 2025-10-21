import { defineStore } from "pinia";
import axios from "axios";
import { useCartStore } from "./cart";
import { useUserStore } from "./user";

export const useAuthStore = defineStore("auth", {
    state: () => {
        return {
            loggingIn: false,
            loggedIn: false,
            role: "guest",
            errors: {},
        } as auth;
    },
    getters: {
        user: () => {
            const user = useUserStore();
            return user;
        },
    },
    actions: {
        async registerNewUser(name: string, email: string, password: string) {
            try {
                this.loggingIn = true;
                const response = await axios.post("/register", {
                    name,
                    email,
                    password,
                });

                this.errors = response.data.errors;
                if (response.status === 200) {
                    if (response.data.message === "success") {
                        const user = useUserStore();
                        user.update(response.data.data.user);
                        this.loggedIn = true;
                    }
                }
                this.loggingIn = false;
            } catch (error) {
                // let the form component display the error
                this.loggingIn = false;
                return error;
            }
        },
        async login(email: string, password: string) {
            try {
                this.loggingIn = true;
                const response = await axios.post("/login", {
                    email,
                    password,
                });

                this.errors = response.data.errors;
                if (response.status === 200) {
                    if (response.data.message === "success") {
                        const user = useUserStore();
                        user.set(response.data.data.user);
                        this.loggedIn = true;
                    }
                }
                this.loggingIn = false;
            } catch (e) {
                this.loggingIn = false;
                if (axios.isAxiosError(e)) {
                    this.errors = e.response?.data.errors;
                }
                // let the form component display the error
                return e;
            }
        },
        async logout() {
            try {
                this.loggingIn = true;
                const response = await axios.post("/logout");

                if (response.status === 204 || response.status === 401) {
                    this.loggedIn = false;
                    const user = useUserStore();
                    user.$reset();
                }
                this.loggingIn = false;
            } catch (e) {
                this.loggingIn = false;
                if (axios.isAxiosError(e)) {
                    if (e.response?.data.message === "Unauthenticated.") {
                        this.loggedIn = false;
                        const user = useUserStore();
                        user.$reset();
                    }
                }
                return e;
            }
        },
        async refresh() {
            try {
                const response = await axios.get("/api/user");
                if (response.status === 200) {
                    if (response.data.data === null) {
                        const user = useUserStore();
                        user.$reset();
                        this.loggedIn = false;
                    } else {
                        const user = useUserStore();
                        user.set(response.data.data);
                        this.loggedIn = true;

                        const cart = useCartStore();
                        cart.fetchCart();
                    }
                }
            } catch (e) {
                console.log(e);
                const user = useUserStore();
                user.$reset();
                this.loggedIn = false;
            }
        },
    },
});

interface auth {
    loggingIn: boolean;
    loggedIn: boolean;
    role: string;
    errors: {
        email: string[];
        password: string[];
    };
}
