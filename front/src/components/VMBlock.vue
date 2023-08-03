<template>
  <div
    class="vm-block"
    :class="{
      'is-in': props.in,
      'is-vertical': props.direction === 'vertical',
      'is-btn': props.isBtn
    }"
    :style="bStyle"
    @click="clickBtn">
    <span class="vm-mess" v-if="message">{{ message }}</span>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { getPropsStyle } from '@/utils/commonUtil';

interface VMBlockProps {
  in?: boolean;
  width?: number | string;
  height?: number | string;
  radius?: number | string;
  padding?: number | string;
  message?: string;
  fontSize?: number | string;
  fontFamily?: string;
  direction?: string;
  isBtn?: boolean;
}
const props = withDefaults(defineProps<VMBlockProps>(), {
  in: false,
  width: undefined,
  height: undefined,
  radius: undefined,
  fontSize: undefined,
  direction: 'horizontal',
  fontFamily: undefined,
  isBtn: false
});

const bStyle = computed(() => {
  const style: any = {};
  props.width && (style.width = getPropsStyle(props.width));
  props.height && (style.height = getPropsStyle(props.height));
  props.radius && (style.borderRadius = getPropsStyle(props.radius));
  props.padding && (style.padding = getPropsStyle(props.padding));
  props.fontSize && (style.fontSize = getPropsStyle(props.fontSize));
  props.fontFamily && (style.fontFamily = props.fontFamily);
  return style;
});

const clickBtn = () => {
  if (props.isBtn) emits('onClick');
};

const emits = defineEmits(['onClick']);
</script>

<style lang="less" scoped>
.vm-block {
  user-select: none;
  height: 42px;
  border-radius: 12px;
  transition: box-shadow 0.1s linear, transform 0.1s linear, background-color 0.2s ease-in-out;
  background-color: var(--bg);
  display: flex;
  align-items: center;
  justify-content: center;
  box-sizing: border-box;
  padding: 0 12px;
  &.is-vertical {
    writing-mode: vertical-lr;
  }
  &:not(.is-in) {
    box-shadow: -6px -6px 10px -1px var(--wshadow70), 6px 6px 10px -1px var(--bshadow15);
  }
  &.is-in {
    box-shadow: inset 2px 2px 3px 0px var(--wshadow1), inset -2px -2px 3px 0px var(--wshadow0);
  }
  &.is-btn {
    cursor: pointer;
    &:active {
      transform: translate(2px, 2px) scale(0.9);
      box-shadow: -0.5px -0.5px 0px 0px var(--wshadow0), 0px 2px 3px -10px var(--bshadow5),
        0.5px 0.5px 0px 0px var(--bshadow15), inset -4px -4px 6px -1px var(--wshadow70),
        inset 4px 4px 6px -1px var(--bshadow20);
    }
  }
  .vm-mess {
    color: var(--primary-text);
  }
}
</style>
