<template>
  <div class="vm-work-box" :style="wStyle">
    <div class="vm-work-title">
      <span class="text">名下作品</span>
    </div>
    <div class="vm-work-content">
      <div class="vm-work-list">
        <VMWorkItem
          v-for="item of workList"
          :key="item.id"
          :labels="item.labels"
          :logo="item.logoUrl"
          :name="item.name"
          :tip="item.tip"
          :article="item.article"
          :stars="item.stars"
          :date="item.compDate" />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { getPropsStyle } from '@/utils/commonUtil';
import VMWorkItem from './VMWorkItem.vue';
import type { Product } from '@/api/indexApi';

interface VMWorkListProps {
  workList?: Product[];
  width?: string | number;
}
const props = withDefaults(defineProps<VMWorkListProps>(), {
  workList: () => [],
  width: undefined
});

const wStyle = computed(() => {
  const style: any = {};
  props.width && (style.width = getPropsStyle(props.width));
  return style;
});
</script>

<style lang="less" scoped>
.vm-work-box {
  user-select: none;
  width: 640px;
  height: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  box-sizing: border-box;
  padding: 12px;
  border-radius: 12px;
  transition: background-color 0.2s ease-in-out;
  background-color: var(--bg);
  box-shadow: -6px -6px 10px -1px var(--wshadow70), 6px 6px 10px -1px var(--bshadow15);
}
.vm-work-title {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  width: 100%;
  margin-bottom: 12px;
  .text {
    margin-left: 4px;
    font-size: 27px;
    font-family: 'YouSheBiaoTiHei';
    color: var(--primary-text);
  }
}
.vm-work-content {
  width: 100%;
  height: 100%;
  border-radius: 12px;
  box-sizing: border-box;
  display: flex;
  overflow-x: hidden;
  overflow-y: auto;
  padding: 16px 8px;
  &::-webkit-scrollbar {
    display: none;
  }
  .vm-work-list {
    // z-index: -1;
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
    // height: 100%;
    box-sizing: border-box;
    &::after {
      content: '';
      display: block;
      width: 100%;
      height: 16px;
      flex-shrink: 0;
    }
  }
}
</style>
