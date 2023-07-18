<template>
  <div
    class="vm-block"
    :class="{
      'is-in': props.in
    }"
    :style="bStyle">
    <span class="vm-mess" v-if="message">{{ message }}</span>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';

interface VMBlockProps {
  in?: boolean;
  width?: number | string;
  height?: number | string;
  radius?: number | string;
  padding?: number | string;
  message?: string;
}
const props = withDefaults(defineProps<VMBlockProps>(), {
  in: false,
  width: undefined,
  height: undefined,
  radius: undefined
});

const bStyle = computed(() => {
  const style: any = {};
  props.width && (style.width = getPropsStyle(props.width));
  props.height && (style.height = getPropsStyle(props.height));
  props.radius && (style.borderRadius = getPropsStyle(props.radius));
  props.padding && (style.padding = getPropsStyle(props.padding));
  return style;
});

const getPropsStyle = (val: number | string) => {
  if (typeof val === 'number') return `${val}px`;
  else return val;
};
</script>

<style lang="less" scoped>
.vm-block {
  user-select: none;
  height: 42px;
  border-radius: 12px;
  background-color: var(--bg);
  display: flex;
  align-items: center;
  justify-content: center;
  &:not(.is-in) {
    box-shadow: -6px -6px 10px -1px var(--t-bshadow), 6px 6px 10px -1px var(--b-bshadow);
  }
  &.is-in {
    box-shadow: inset 2px 2px 3px 0px var(--it-bshadow), inset -2px -2px 3px 0px var(--ib-bshadow);
  }
  .vm-mess {
    font-size: 16px;
    color: var(--primary-text);
  }
}
</style>
