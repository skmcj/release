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
  fontSize?: number | string;
}
const props = withDefaults(defineProps<VMBlockProps>(), {
  in: false,
  width: undefined,
  height: undefined,
  radius: undefined,
  fontSize: undefined
});

const bStyle = computed(() => {
  const style: any = {};
  props.width && (style.width = getPropsStyle(props.width));
  props.height && (style.height = getPropsStyle(props.height));
  props.radius && (style.borderRadius = getPropsStyle(props.radius));
  props.padding && (style.padding = getPropsStyle(props.padding));
  props.fontSize && (style.fontSize = getPropsStyle(props.fontSize));
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
  transition: background-color 0.2s ease-in-out;
  background-color: var(--bg);
  display: flex;
  align-items: center;
  justify-content: center;
  box-sizing: border-box;
  padding: 0 12px;
  &:not(.is-in) {
    box-shadow: -6px -6px 10px -1px var(--wshadow70), 6px 6px 10px -1px var(--bshadow15);
  }
  &.is-in {
    box-shadow: inset 2px 2px 3px 0px var(--wshadow1), inset -2px -2px 3px 0px var(--wshadow0);
  }
  .vm-mess {
    font-size: 16px;
    color: var(--primary-text);
  }
}
</style>
