<template>
  <div class="vm-btn" :style="btnStyle" @click.stop="onClick">
    <i v-if="iconClass" class="vm-btn_icon" :class="iconClass"></i>
    <span v-if="text" class="vm-icon_text">{{ text }}</span>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { getPropsStyle } from '@/utils/commonUtil';

interface VMButtonProps {
  width?: number | string;
  height?: number | string;
  radius?: number | string;
  color?: string;
  text?: string;
  iconClass?: string;
  fontSize?: number | string;
}
const props = withDefaults(defineProps<VMButtonProps>(), {
  width: undefined,
  height: undefined,
  radius: undefined,
  color: undefined,
  text: undefined,
  iconClass: undefined
});

const btnStyle = computed(() => {
  const style: any = {};
  props.width && (style.width = getPropsStyle(props.width));
  props.height && (style.height = getPropsStyle(props.height));
  props.radius && (style.borderRadius = getPropsStyle(props.radius));
  props.fontSize && (style.fontSize = getPropsStyle(props.fontSize));
  props.color && (style.color = props.color);
  return style;
});

const onClick = () => {
  emits('onClick');
};

const emits = defineEmits(['onClick']);
</script>

<style lang="less" scoped>
.vm-btn {
  cursor: pointer;
  user-select: none;
  width: 42px;
  height: 42px;
  border-radius: 50%;
  box-sizing: border-box;
  background-color: var(--bg);
  padding: 8px;
  transition: box-shadow 0.2s linear, transform 0.1s linear, background-color 0.2s ease-in-out;
  box-shadow: -6px -6px 10px -1px var(--wshadow70), 6px 6px 10px -1px var(--bshadow15);
  display: flex;
  align-items: center;
  justify-content: center;
  &:hover {
    box-shadow: -2px -2px 5px 0px var(--wshadow70), 2px 2px 5px 0px var(--bshadow15);
  }
  &:active {
    transform: translate(2px, 2px) scale(0.9);
    box-shadow: -0.5px -0.5px 0px 0px var(--wshadow0), 0px 2px 3px -10px var(--bshadow5),
      0.5px 0.5px 0px 0px var(--bshadow15), inset -4px -4px 6px -1px var(--wshadow70),
      inset 4px 4px 6px -1px var(--bshadow20);
  }
  color: var(--primary-text);
  .vm-btn_icon {
    font-size: 18px;
    & + .vm-btn_text {
      margin-left: 6px;
    }
    &.ir-bilibili {
      font-size: 12px;
    }
  }

  .vm-btn_text {
    font-size: 16px;
  }
}
</style>
