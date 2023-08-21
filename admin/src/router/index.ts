import { createRouter, createWebHistory } from 'vue-router';
import { useRouteInfoStore } from '@/stores/routeinfo';
import LoginView from '@/views/LoginView.vue';
import MainView from '@/views/MainView.vue';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/login',
      name: 'login',
      component: LoginView
    },
    {
      path: '/',
      name: 'main',
      component: MainView,
      children: [
        {
          path: '/',
          alias: ['/user', '/index'],
          name: 'user',
          meta: {
            title: '用户管理'
          },
          component: () => import('@/views/UserView.vue')
        },
        {
          path: '/sentence',
          name: 'sentence',
          meta: {
            title: '句子管理'
          },
          component: () => import('@/views/SentenceView.vue')
        },
        {
          path: '/image',
          name: 'image',
          meta: {
            title: '图片管理'
          },
          component: () => import('@/views/ImageView.vue')
        },
        {
          path: '/level',
          name: 'level',
          meta: {
            title: '境界管理'
          },
          component: () => import('@/views/LevelView.vue')
        },
        {
          path: '/comment',
          name: 'comment',
          meta: {
            title: '留言管理'
          },
          component: () => import('@/views/CommentView.vue')
        },
        {
          path: '/product',
          name: 'product',
          meta: {
            title: '作品管理'
          },
          component: () => import('@/views/ProductView.vue')
        },
        {
          path: '/role',
          name: 'role',
          meta: {
            title: '角色管理'
          },
          component: () => import('@/views/RoleView.vue')
        }
      ]
    }
  ]
});

router.beforeEach((to, from) => {
  const { setRouteInfo } = useRouteInfoStore();
  setRouteInfo({
    path: to.path,
    name: to.name ?? '',
    meta: to.meta
  });
});

export default router;
