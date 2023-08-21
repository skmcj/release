<template>
  <header class="main-header" :class="{ 'show-all': menuVisible }">
    <MenuIcon v-model="menuVisible" />
    <div class="main-header-list">
      <div
        class="main-header-item"
        v-for="(item, index) in menuList"
        :key="item.id"
        :class="{ 'is-active': activeIndex === index }"
        @click.stop="checked(index)">
        <i class="icon" :class="item.icon"></i>
        <span class="text">{{ item.text }}</span>
      </div>
    </div>
  </header>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useRouteInfoStore } from '@/stores/routeinfo';
import { storeToRefs } from 'pinia';
import MenuIcon from '@/components/icon/MenuIcon.vue';

// 用户管理、每日一句管理、每日一图管理、境界管理、留言管理、作品管理、角色管理

const menuVisible = ref(false);

const { activeIndex } = storeToRefs(useRouteInfoStore());

const router = useRouter();

const checked = (index: number) => {
  router.push(menuList[index].path);
};

const menuList = [
  {
    id: 'e63748d5992d1',
    icon: 'ir-user',
    text: '用户管理',
    path: '/user'
  },
  {
    id: 'e63748d5992d2',
    icon: 'ir-sentence',
    text: '句子管理',
    path: '/sentence'
  },
  {
    id: 'e63748d5992d3',
    icon: 'ir-image',
    text: '图片管理',
    path: '/image'
  },
  {
    id: 'e63748d5992d4',
    icon: 'ir-level',
    text: '境界管理',
    path: '/level'
  },
  {
    id: 'e63748d5992d5',
    icon: 'ir-comment',
    text: '留言管理',
    path: '/comment'
  },
  {
    id: 'e63748d5992d6',
    icon: 'ir-product',
    text: '作品管理',
    path: '/product'
  },
  {
    id: 'e63748d5992d7',
    icon: 'ir-role',
    text: '角色管理',
    path: '/role'
  }
];
</script>

<style lang="scss" scoped>
.main-header {
  font-size: 14px;
  width: 5rem;
  height: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  background-color: $rlh-bg;
  box-sizing: border-box;
  padding: 21px 0;
  transition: width 0.2s ease-in-out;
  &.show-all {
    width: 9.375rem;
    .main-header-item {
      justify-content: center;
      &.is-active {
        &::before {
          width: 96%;
          height: 80%;
        }
      }
      .icon {
        width: 2.1rem;
        font-size: 1.8rem;
      }
    }
  }
}
.main-header-list {
  width: 100%;
  overflow: hidden;
  margin-top: 36px;
  display: flex;
  flex-direction: column;
  box-sizing: border-box;
  padding: 0 0.5rem;
  .main-header-item {
    position: relative;
    width: 100%;
    display: flex;
    align-items: center;
    color: $rlh-text;
    column-gap: 0.5rem;
    &::before {
      z-index: 0;
      content: '';
      position: absolute;
      width: 0%;
      height: 0%;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      transition: all 0.3s ease-in-out;
      border-radius: 0.5rem;
    }
    &.is-active {
      &::before {
        width: 80%;
        height: 80%;
        background-color: $rlh-checked;
      }
    }
  }
  .icon {
    position: relative;
    transition: width 0.3s ease-in-out, font-size 0.3s ease-in-out;
    width: 4rem;
    height: 4rem;
    font-size: 2.1rem;
    flex-shrink: 0;
    line-height: 4rem;
    text-align: center;
  }
  .text {
    user-select: none;
    position: relative;
    flex-shrink: 0;
    font-size: 1rem;
  }
}
</style>
