<template>
  <Teleport :to="to">
    <Transition name="vm-slidein">
      <div
        class="vm-popper"
        :style="pStyle"
        v-if="modelValue"
        @mouseenter="clearTimer"
        @mouseleave="startTimer"
        @touchstart="clearTimer"
        @touchend="startTimer">
        <slot></slot>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup lang="ts">
import { computed, watch } from 'vue';

interface VMPopperProps {
  modelValue?: boolean;
  to?: string;
  inset?: string;
  duration?: number;
}
const props = withDefaults(defineProps<VMPopperProps>(), {
  modelValue: false,
  to: 'body',
  inset: undefined,
  // 0 表示不自动关闭
  duration: 0
});

const pStyle = computed(() => {
  const style: any = {};
  props.inset && (style.inset = props.inset);
  return style;
});

watch(
  () => props.modelValue,
  () => {
    if (props.modelValue) startTimer();
  }
);

let dTimer: number | undefined;

function startTimer() {
  if (props.duration === 0) return;
  clearTimer();
  dTimer = setTimeout(() => {
    close();
  }, props.duration);
}

function clearTimer() {
  dTimer && clearTimeout(dTimer);
}

function close() {
  emits('update:modelValue', false);
}

const emits = defineEmits(['update:modelValue']);
</script>

<style lang="less">
.vm-popper {
  top: 0;
  left: 0;
  position: absolute;
  z-index: 999;
  box-sizing: border-box;
  padding: 5px;
  border-radius: 5px;
  overflow: hidden;
  background-color: var(--select-bg);
  box-shadow: 2px 2px 8px var(--bshadow15);
}

// 星座下拉栏
.vm-ss-select-list {
  display: flex;
  flex-direction: column;
  box-sizing: border-box;
  padding: 5px 0;
  max-height: 180px;
  overflow-y: auto;
  background-color: var(--select-bg);
  box-shadow: inset -3px -3px 6px 0px var(--wshadow70), inset 3px 3px 6px 0px var(--bshadow15);
  border-radius: 5px;
  &::-webkit-scrollbar {
    width: 6px;
    height: 6px;
    display: none;
  }
  .vm-ss-select-item {
    cursor: pointer;
    display: flex;
    font-size: 12px;
    color: var(--primary-text);
    flex-shrink: 0;
    box-sizing: border-box;
    padding: 0 8px;
    transition: background-color 0.3s ease-in-out;
    &:hover {
      background-color: var(--select-hbg);
    }
    &.is-active {
      color: var(--select-ac);
    }
    .label {
      white-space: nowrap;
    }
    .icon {
      margin-left: 5px;
    }
    .value {
      margin-left: 5px;
    }
  }
}
</style>
