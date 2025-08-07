import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
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
    base_price: string;
    imageUrl: string;
    short: string;
    link: string;
    configuration: Productconfiguration;
}

export interface Cart {
    list: [
        {
            quantity: integer;
            product: Product;
            config: ConfOption;
        }
    ]
}
