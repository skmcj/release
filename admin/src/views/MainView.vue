<template>
  <div class="main-view">
    <!-- 左侧导航 -->
    <Header />
    <div class="main-container">
      <!-- 顶部标题栏 -->
      <Nav />
      <div class="main-content">
        <!-- 内容视图 -->
        <router-view v-slot="{ Component }">
          <transition name="rl-pagein" mode="out-in">
            <component :is="Component" />
          </transition>
        </router-view>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { RouterView } from 'vue-router';
import Header from '@/components/Header.vue';
import Nav from '@/components/Nav.vue';
</script>

<style lang="scss" scoped>
.main-view {
  width: 100vw;
  height: 100vh;
  display: flex;
  background-color: $rlbg-default;
}
.main-container {
  flex-grow: 1;
  height: 100%;
  display: flex;
  flex-direction: column;
}
.main-content {
  width: 100%;
  height: 100%;
  overflow: auto;
  box-sizing: border-box;
  padding: 18px 24px;
  box-shadow: inset 2px 2px 5px 0 rgba(0, 0, 0, 0.3);
  &::-webkit-scrollbar {
    width: 5px;
    height: 5px;
  }
  &::-webkit-scrollbar-thumb {
    background-color: #8d8d8d;
    border-radius: 2.5px;
  }
}
</style>
