import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
    cartItems: number;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export type AppPageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
};

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    isAdmin: boolean;
    isTeam: boolean;
    bio: string;
    url: string;
    imageUrl: string;
    role: string;
}

export type BreadcrumbItemType = BreadcrumbItem;

export interface ConfOption {
    name: string,
    price: integer
}

export interface Productconfiguration {
    options:
    [
        {
            name: string;
            options:
            [
                ConfOption
            ];
            ref: string;
            type: string;
        }
    ]
}

export interface Product {
    id: number;
    type: string;
    name: string;
    code: string;
    description: string;
    base_price: number;
    quantity: number;
    base_quantity: number;
    short: string;
    link: string;
    configuration: Productconfiguration;
    images: array<Image>;
    default_images:Image;
    created: string;
}
export interface Image {
    path: string;
}

export interface Cart_item {
    id:number;
    product: Product;
    quantity: number;
}

export interface Cart {
    items: Array<Cart_item>
}

