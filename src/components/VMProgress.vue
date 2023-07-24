<template>
  <div class="vm-progress show-title" :data-title="`${percentage}%`">
    <span class="vm-progress_label" v-if="label">{{ label }}</span>
    <div class="vm-progress-box">
      <div class="vm-progress-inner" :style="innerStyle"></div>
    </div>
    <span class="vm-progress-tag" v-if="showTag">{{ `${percentage}%` }}</span>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';

interface VMProgressProps {
  percentage?: number;
  showTag?: boolean;
  label?: string;
  color?: string[] | string;
  cDeg?: number;
}
const props = withDefaults(defineProps<VMProgressProps>(), {
  percentage: 0,
  showTag: false,
  label: undefined,
  color: undefined,
  cDeg: 180
});

const innerStyle = computed(() => {
  const style: any = {};
  style.width = `${props.percentage}%`;
  if (props.color) {
    if (typeof props.color === 'string') {
      style.background = props.color;
    } else {
      style.background = `linear-gradient(${props.cDeg}deg, ${props.color.join(',')});`;
    }
  }
  return style;
});
</script>

<style lang="less" scoped>
.vm-progress {
  display: flex;
  align-items: center;
  font-family: 'zcool-kuaile';
  .vm-progress_label {
    margin-right: 12px;
    font-size: 14px;
    color: var(--primary-text);
  }
  .vm-progress-box {
    position: relative;
    flex-grow: 1;
    height: 8px;
    border-radius: 4px;
    background-color: var(--bg);
    overflow: hidden;
    box-shadow: inset -2px -2px 5px 0px var(--pshadow1), inset 2px 2px 3px 0px var(--pshadow2);
    .vm-progress-inner {
      position: absolute;
      left: 0;
      top: 0;
      width: 0%;
      height: 8px;
      border-radius: 4px;
      background: var(--progress-bg);
      box-shadow: 1px 1px 5px 0px var(--pshadow2);
      transition: width 0.5s ease-in-out;
    }
  }
  .vm-progress-tag {
    margin-left: 8px;
    font-size: 12px;
    color: var(--light-gray);
  }
}
</style>
