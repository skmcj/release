<template>
  <div class="vm-work-card-item" :style="iStyle"></div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { getPropsStyle } from '@/utils/commonUtil';

interface VMBlockProps {
  width?: number | string;
  height?: number | string;
  radius?: number | string;
}
const props = withDefaults(defineProps<VMBlockProps>(), {
  width: undefined,
  height: undefined,
  radius: undefined
});

const iStyle = computed(() => {
  const style: any = {};
  props.width && (style.width = getPropsStyle(props.width));
  props.height && (style.height = getPropsStyle(props.height));
  props.radius && (style.borderRadius = getPropsStyle(props.radius));
  return style;
});
</script>

<style lang="less" scoped>
.vm-work-card-item {
  width: 155px;
  height: 200px;
  box-sizing: border-box;
  border-radius: 12px;
  box-shadow: inset 3px 3px 6px 0px var(--wshadow1), inset -3px -3px 6px 0px var(--wshadow0);
}
</style>
