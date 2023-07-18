<template>
  <div class="main">
    <div class="top" ref="topBox">
      <HeaderSvg class="bg" />
      <div class="top-content">
        <div class="left">
          <Avatar />
        </div>
        <div class="right">
          <div class="top-mess">
            <div class="top-mess_content">
              <VMBlock class="top-mess-item" padding="0 33px" message="SKMCJ" radius="21px" in />
              <VMBlock class="top-mess-item" padding="0 33px" message="Make In China" radius="21px" in />
            </div>
            <div class="top-mess_mode"></div>
          </div>
          <div class="top-tools"></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import HeaderSvg from '@/components/HeaderSvg.vue';
import Avatar from '@/components/Avatar.vue';
import VMBlock from '@/components/VMBlock.vue';

const topBox = ref(null);

onMounted(() => {});
</script>

<style lang="less" scoped>
.top {
  position: relative;
  width: 100%;
}
.bg {
  filter: drop-shadow(0px 3px 8px var(--b-bshadow));
}
.top-content {
  position: absolute;
  top: 0;
  left: 50%;
  transform: translateX(-50%);
  width: 62.5%;
  height: 98%;
  box-sizing: border-box;
  display: flex;
  .left {
    position: relative;
    width: 22%;
    height: 100%;
  }
  .right {
    position: relative;
    width: 78%;
    height: 100%;
    display: flex;
    flex-direction: column;
  }
}
.left {
  :deep(.avatar-box) {
    position: absolute;
    top: 18%;
    right: 21%;
  }
}
.right {
  .top-mess {
    width: 100%;
    height: 42%;
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    .top-mess_content {
      display: flex;
      padding-bottom: 18px;
    }
    .top-mess-item {
      & + .top-mess-item {
        margin-left: 24px;
      }
    }
    .top-mess_mode {
    }
  }
  .top-tools {
    width: 100%;
    height: 58%;
  }
}
@media (max-width: 1080px) {
  .top-content {
    width: 62.5%;
  }
}
@media (min-width: 1081px) and (max-width: 1680px) {
  .top-content {
    width: 1000px;
  }
}
@media (min-width: 1681px) and (max-width: 1920px) {
  .top-content {
    width: 1200px;
  }
}
@media (min-width: 1921px) {
  .top-content {
    width: 62.5%;
  }
}
</style>
