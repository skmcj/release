<template>
  <div class="vm-work-box" :style="wStyle">
    <div class="vm-work-title">
      <span class="text">名下作品</span>
    </div>
    <div class="vm-work-content">
      <div class="vm-work-list">
        <VMH5WorkItem
          width="40vw"
          height="52vw"
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
import VMH5WorkItem from './VMH5WorkItem.vue';
import type { Product } from '@/api/indexApi';

interface VMWorkH5ListProps {
  width?: number | string;
  workList?: Product[];
}
const props = withDefaults(defineProps<VMWorkH5ListProps>(), {
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
  padding-bottom: 16px;
  .vm-work-list {
    // z-index: -1;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    row-gap: 16px;
    width: 100%;
    // height: 100%;
    box-sizing: border-box;
  }
  .vm-work-i {
  }
}
</style>
