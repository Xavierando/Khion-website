import { createRouter, createWebHistory } from 'vue-router';

import { useAuthStore } from '@/stores/auth';

const routes = [
  {
    path: '/',
    component: () => import('@/pages/Welcome.vue'),
  },
  {
    path: '/login',
    component: () => import('@/pages/Login.vue'),
    meta: { requiresGuest: true },
  },
  {
    path: '/prodotti',
    component: () => import('@/pages/Prodotti.vue'),
  },
  {
    path: '/prodotti/:id',
    component: () => import('@/pages/Prodotto.vue'),
    props: true,
  },
  {
    path: '/profilo',
    name: 'profilo',
    component: () => import('@/pages/settings/Profilo.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/settings/prodotti',
    name: 'gestisciProdotti',
    component: () => import('@/pages/settings/GestisciProdotti.vue'),
    meta: { requiresAdmin: true },
  },
  {
    path: '/settings/prodotti/:id',
    name: 'editProdotto',
    component: () => import('@/pages/settings/ModificaProdotto.vue'),
    meta: { requiresAdmin: true },
    props: true,
  },
  {
    path: '/checkout/:session_id',
    name: 'checkout',
    component: () => import('@/pages/Acquisto.vue'),
    meta: { requiresAuth: true },
    props: true,
  },
  {
    path: '/settings/ordini',
    name: 'gestisciOrdini',
    component: () => import('@/pages/settings/Ordini.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/settings/ordini/:id',
    name: 'editOrdine',
    component: () => import('@/pages/settings/Ordine.vue'),
    meta: { requiresAuth: true },
    props: true,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const auth = useAuthStore();
  if (to.meta.requiresAuth && !auth.loggedIn) {
    next({ path: '/' });
  }

  if (to.meta.requiresAdmin && !auth.user.isAdmin) {
    next({ path: '/' });
  }
  if (to.meta.requiresGuest && auth.loggedIn) {
    next(from.path);
  } else {
    next();
  }
});

export default router;
