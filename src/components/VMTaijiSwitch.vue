<template>
  <div class="vm-taiji-switch" :style="tStyle" :class="{ 'is-active': modelValue }">
    <div class="vm-taiji-inner" @click.stop="changeValue">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 180 180" class="vm-taiji-icon">
        <g id="taiji">
          <path
            id="tj-yin"
            d="M90,0a90,90,0,1,0,90,90A90,90,0,0,0,90,0Zm0,40.8A8.2,8.2,0,1,1,81.8,49,8.2,8.2,0,0,1,90,40.8ZM90,172c-.71,0-1.41,0-2.11-.05A41,41,0,0,1,90,90,41,41,0,0,0,92.11,8.05,82,82,0,0,1,90,172Z" />
          <circle id="tj-yang" cx="90" cy="139.2" r="8.2" />
        </g>
      </svg>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { getPropsStyle } from '@/utils/commonUtil';

interface VMTaijiSwitchProps {
  modelValue?: boolean;
  size?: string | number;
}
const props = withDefaults(defineProps<VMTaijiSwitchProps>(), {
  modelValue: false,
  size: undefined
});

const tStyle = computed(() => {
  const style: any = {};
  props.size && (style.width = getPropsStyle(props.size));
  props.size && (style.height = getPropsStyle(props.size));
  return style;
});

const changeValue = () => {
  emits('update:modelValue', !props.modelValue);
};

const emits = defineEmits(['update:modelValue']);
</script>

<style lang="less" scoped>
.vm-taiji-switch {
  width: 108px;
  height: 108px;
  transition: box-shadow 0.1s linear, transform 0.1s linear, background-color 0.2s ease-in-out;
  background-color: var(--bg);
  display: flex;
  align-items: center;
  justify-content: center;
  box-sizing: border-box;
  padding: 6px;
  border-radius: 50%;
  box-shadow: -6px -6px 10px -1px var(--wshadow70), 6px 6px 10px -1px var(--bshadow15);
  .vm-taiji-inner {
    width: 92%;
    height: 92%;
    border-radius: 50%;
    box-shadow: inset -3px -3px 6px 0 var(--wshadow70), inset 3px 3px 6px 0 var(--bshadow15);
    box-sizing: border-box;
    padding: 3px;
  }
  .vm-taiji-icon {
    transition: transform 0.3s ease-in-out;
    fill: var(--primary-text);
  }
  &.is-active {
    .vm-taiji-icon {
      transform: rotate(180deg);
    }
  }
}
</style>
