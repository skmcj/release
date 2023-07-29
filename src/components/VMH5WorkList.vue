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
          :labels="workItem.labels"
          :logo="workItem.logo"
          :name="workItem.name"
          :tip="workItem.tip"
          :date="workItem.date" />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { getPropsStyle } from '@/utils/commonUtil';
import VMH5WorkItem from './VMH5WorkItem.vue';
import type { WorkItem } from '@/utils/commonType';

interface VMWorkH5ListProps {
  width?: number | string;
  workList?: WorkItem[];
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

const workItem = {
  logo: 'https://s.cky.qystu.cc/gh/skmcj/pic-bed@main/common/small-logo.png',
  name: '颜典',
  tip: '一款辅助设计师的颜色搭配工具，包含海量色卡信息',
  date: '2023-05-18',
  labels: [
    {
      icon: 'code',
      tip: '源码',
      color: '#21B351'
    },
    {
      icon: 'link',
      tip: '体验地址',
      color: '#6F94CD',
      link: 'https://color.skmcj.top'
    }
  ]
};
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
