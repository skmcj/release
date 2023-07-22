<template>
  <div class="vm-switch" :class="{ 'is-active': modelValue }">
    <div class="vm-switch_inner" @click.stop="changeValue"></div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';

interface VMSwitchProps {
  modelValue?: boolean;
}
const props = withDefaults(defineProps<VMSwitchProps>(), {
  modelValue: false
});

const changeValue = () => {
  console.log('change');
  emits('update:modelValue', !props.modelValue);
};

const emits = defineEmits(['update:modelValue']);
</script>

<style lang="less" scoped>
.vm-switch {
  overflow: hidden;
  position: relative;
  width: 72px;
  height: 32px;
  border-radius: 21px;
  background-color: var(--bg);
  box-shadow: 6px 3px 8px 0px var(--wshadow2), -6px -3px 6px 0px var(--wshadow0),
    inset -4px -4px 4px 0px var(--wshadow0), inset 4px 4px 4px 0px var(--wshadow2);
  &.large {
    width: 100px;
    height: 42px;
    .vm-switch_inner {
      left: -58px;
    }
    &.is-active {
      .vm-switch_inner {
        left: 58px;
      }
    }
  }
  &.small {
    width: 56px;
    height: 24px;
    .vm-switch_inner {
      left: -32px;
    }
    &.is-active {
      .vm-switch_inner {
        left: 32px;
      }
    }
  }
  .vm-switch_inner {
    position: absolute;
    height: 100%;
    width: 100%;
    left: -40px;
    border-radius: 16px;
    transition: all 0.3s ease-in-out;
    background-color: var(--bg);
    box-shadow: 6px 3px 8px 0px var(--wshadow2), -6px -3px 6px 0px var(--wshadow0);
  }
  &.is-active {
    .vm-switch_inner {
      left: 40px;
    }
  }
}
</style>
