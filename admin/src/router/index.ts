import { createRouter, createWebHistory } from 'vue-router';
import { useRouteInfoStore } from '@/stores/routeinfo';
import { useRoleInfoStore } from '@/stores/roleinfo';
import LoginView from '@/views/LoginView.vue';
import MainView from '@/views/MainView.vue';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/login',
      name: 'login',
      component: LoginView,
      meta: {
        title: '登录',
        bof: 'login'
      }
    },
    {
      path: '/',
      name: 'main',
      component: MainView,
      meta: {
        title: '管理后台',
        bof: 'main'
      },
      children: [
        {
          path: '/',
          alias: ['/user', '/index'],
          name: 'user',
          meta: {
            title: '用户管理',
            bof: 'user'
          },
          component: () => import('@/views/user/UserView.vue')
        },
        {
          path: '/user/add',
          name: 'addUser',
          meta: {
            title: '用户管理 / 添加用户',
            bof: 'user'
          },
          component: () => import('@/views/user/UserEditView.vue')
        },
        {
          path: '/user/edit',
          name: 'editUser',
          meta: {
            title: '用户管理 / 编辑用户',
            bof: 'user'
          },
          component: () => import('@/views/user/UserEditView.vue')
        },
        {
          path: '/social',
          name: 'social',
          meta: {
            title: '用户管理 / 社交信息',
            bof: 'user'
          },
          component: () => import('@/views/user/SocialView.vue')
        },
        {
          path: '/sentence',
          name: 'sentence',
          meta: {
            title: '句子管理',
            bof: 'sentence'
          },
          component: () => import('@/views/sentence/SentenceView.vue')
        },
        {
          path: '/image',
          name: 'image',
          meta: {
            title: '图片管理',
            bof: 'image'
          },
          component: () => import('@/views/image/ImageView.vue')
        },
        {
          path: '/image/add',
          name: 'addImage',
          meta: {
            title: '图片管理 | 添加图片',
            bof: 'image'
          },
          component: () => import('@/views/image/EditImageView.vue')
        },
        {
          path: '/image/edit',
          name: 'editImage',
          meta: {
            title: '图片管理 | 编辑图片',
            bof: 'image'
          },
          component: () => import('@/views/image/EditImageView.vue')
        },
        {
          path: '/image/batch',
          name: 'batchImage',
          meta: {
            title: '图片管理 | 批量添加',
            bof: 'image'
          },
          component: () => import('@/views/image/BatchImageView.vue')
        },
        {
          path: '/level',
          name: 'level',
          meta: {
            title: '境界管理',
            bof: 'level'
          },
          component: () => import('@/views/level/LevelView.vue')
        },
        {
          path: '/comment',
          name: 'comment',
          meta: {
            title: '留言管理',
            bof: 'comment'
          },
          component: () => import('@/views/comment/CommentView.vue')
        },
        {
          path: '/product',
          name: 'product',
          meta: {
            title: '作品管理',
            bof: 'product'
          },
          component: () => import('@/views/product/ProductView.vue')
        },
        {
          path: '/product/label',
          name: 'productLabel',
          meta: {
            title: '作品管理 | 标签管理',
            bof: 'product'
          },
          component: () => import('@/views/product/ProductLabelView.vue')
        },
        {
          path: '/product/add',
          name: 'addProduct',
          meta: {
            title: '作品管理 | 添加作品',
            bof: 'product'
          },
          component: () => import('@/views/product/EditProductView.vue')
        },
        {
          path: '/product/edit',
          name: 'editProduct',
          meta: {
            title: '作品管理 | 编辑作品',
            bof: 'product'
          },
          component: () => import('@/views/product/EditProductView.vue')
        },
        {
          path: '/article',
          name: 'article',
          meta: {
            title: '文章管理',
            bof: 'article'
          },
          component: () => import('@/views/article/ArticleView.vue')
        },
        {
          path: '/role',
          name: 'role',
          meta: {
            title: '角色管理',
            bof: 'role'
          },
          component: () => import('@/views/role/RoleView.vue')
        }
      ]
    }
  ]
});

router.beforeEach((to, from) => {
  const { loginFlag } = useRoleInfoStore();
  if (!loginFlag && to.name !== 'login') {
    return {
      name: 'login'
    };
  }
  if (loginFlag && to.name === 'login') {
    return {
      path: '/'
    };
  }
  const { setRouteInfo } = useRouteInfoStore();
  setRouteInfo({
    path: to.path,
    name: to.name ?? '',
    meta: to.meta
  });
});

export default router;
