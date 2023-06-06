import { createRouter, createWebHistory } from 'vue-router';
import {useAuthStore} from "@/stores/store-auth";

const checkAuthentication = (to, from, next) => {
  const authStore = useAuthStore();
  const user = authStore.getUser;

  if (to.name === 'welcome') {
    if (user) {
      next({ name: user.userType });
    } else {
      next();
    }
  } else if (!user) {
    next({ name: 'login' });
  } else {
    if (user.userType !== to.name) {
      next({ name: user.userType });
    } else {
      next();
    }
  }
};

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
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
      beforeEnter: (to, from, next) => {
        const user = authStore.getUser;
        if (!user) {
          next({ name: 'login' });
        } else {
          if (user.userType !== to.name) {
            next({ name: user.userType });
          } else {
            next();
          }
        }
      },
    },
  ],
});

router.beforeEach(checkAuthentication);

export default router;
