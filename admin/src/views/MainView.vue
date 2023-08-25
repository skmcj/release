<template>
  <div class="main-view">
    <!-- 左侧导航 -->
    <Header />
    <div class="main-container">
      <!-- 顶部标题栏 -->
      <Nav />
      <div class="main-content">
        <div class="main-content-inner">
          <!-- 内容视图 -->
          <router-view v-slot="{ Component }">
            <transition name="rl-pagein" mode="out-in">
              <component :is="Component" />
            </transition>
          </router-view>
        </div>
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
  position: relative;
  // width: 100%;
  // height: 100%;
  flex-grow: 1;
  overflow: auto;
  box-shadow: inset 2px 0 5px 0 rgba(0, 0, 0, 0.3);
  .main-content-inner {
    position: absolute;
    width: 100%;
    height: 100%;
    // box-sizing: border-box;
    // padding: 18px 24px;
  }
  &::-webkit-scrollbar {
    width: 8px;
    height: 10px;
  }
  &::-webkit-scrollbar-thumb {
    background-color: #8d8d8d;
    border-radius: 5px;
  }
}
</style>
