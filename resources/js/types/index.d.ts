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

export type AppPageProps<
  T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
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
  emailVerified: boolean;
}

export type BreadcrumbItemType = BreadcrumbItem;

export interface ConfOption {
  name: string;
  price: integer;
}

export interface Productconfiguration {
  name: string;
  options: [ConfOption];
  ref: string;
  type: string;
  option: string;
}

export interface Tag {
  id: number;
  tag: string;
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
  configuration: Productconfiguration[];
  images: array<Image>;
  default_images: Image | null;
  created: string;
  tags: Tag[];
}
export interface Image {
  id: string | number;
  thumbnail: string;
  src: string;
  caption: string;
}

export interface CartItem {
  id: number;
  product: number;
  quantity: number;
}

export interface Cart {
  items: Array<CartItem>;
}

export interface ConfigurationItem {
  title: string;
  isActive: boolean;
  isVisible: boolean;
}

export interface Order {
  id: number;
  status: string;
  total: number;
  items: CartItem[];
  statusOptions: string;
}
