<template>
  <div class="vm-check-box" :style="cStyle">
    <input type="checkbox" :checked="modelValue" />
    <div class="vm-check-inner" @click.stop="click">
      <i class="radic"></i>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { getPropsStyle } from '@/utils/commonUtil';

interface VMCheckBoxProps {
  modelValue?: boolean;
  size?: number | string;
}
const props = withDefaults(defineProps<VMCheckBoxProps>(), {
  modelValue: false,
  size: undefined
});

const click = () => {
  emits('update:modelValue', !props.modelValue);
};

const cStyle = computed(() => {
  const style: any = {};
  if (props.size) {
    style.width = getPropsStyle(props.size);
    style.height = getPropsStyle(props.size);
  }
  return style;
});

const emits = defineEmits(['update:modelValue']);
</script>

<style lang="less" scoped>
.vm-check-box {
  width: 21px;
  height: 21px;
  background-color: var(--bg);
  input {
    display: none;
  }
  .vm-check-inner {
    width: 100%;
    height: 100%;
    cursor: pointer;
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 3px;
    transition: box-shadow 0.3s ease-in-out;
    box-shadow: -2px -2px 5px 0px var(--wshadow70), 2px 2px 5px 0px var(--bshadow15);
  }
  .radic {
    width: 58%;
    height: 24%;
    display: inline-block;
    border: 1px solid var(--check-text);
    border-width: 0 0 2px 2px;
    transform-origin: 50%;
    transform: rotate(-45deg);
    -ms-transform: rotate(-45deg);
    -moz-transform: rotate(-45deg);
    -webkit-transform: rotate(-45deg);
    -o-transform: rotate(-45deg);
    transition: opacity 0.3s ease-in-out;
    opacity: 0.3;
  }
  input:checked ~ .vm-check-inner {
    box-shadow: -0.5px -0.5px 0px 0px var(--wshadow0), 0.5px 0.5px 0px 0px var(--bshadow15),
      inset -2px -2px 5px 0px var(--wshadow70), inset 2px 2px 5px 0px var(--bshadow15);
    .radic {
      opacity: 1;
    }
  }
}
</style>
