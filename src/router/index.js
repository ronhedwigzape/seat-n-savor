import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '@/stores/store-auth';

const routes = [
  {
    path: '/',
    name: 'welcome',
    component: () => import('../views/Welcome.vue'),
  },
  {
    path: '/admin',
    name: 'admin',
    component: () => import('../views/Admin.vue'),
  },
  {
    path: '/customer',
    name: 'customer',
    component: () => import('../views/Customer.vue'),
  },
  {
    path: '/restaurateur',
    name: 'restaurateur',
    component: () => import('../views/Restaurateur.vue'),
  },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

router.beforeEach((to, from, next) => {
  const user = useAuthStore().getUser;

  if (!user) {
    if (to.name !== 'welcome') {
      next({ name: 'welcome' });
    } else {
      next();
    }
  } else {
    if (user.userType !== to.name) {
      next({ name: user.userType });
    } else {
      next();
    }
  }
});

export default router;
