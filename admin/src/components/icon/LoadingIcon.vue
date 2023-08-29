<template>
  <div
    v-if="!close || modelValue"
    class="loading-icon"
    :class="{
      'is-loading': modelValue
    }">
    <i class="icon ir-zhishixingqiu"></i>
    <span class="loading-icon-tip" v-if="tip">{{ tip }}</span>
  </div>
</template>

<script setup lang="ts">
interface LoadingIconProps {
  modelValue: boolean;
  tip?: string;
  close?: boolean;
}
const props = withDefaults(defineProps<LoadingIconProps>(), {
  modelValue: false,
  tip: undefined,
  close: false
});
const emits = defineEmits(['update:modelValue']);
</script>

<style lang="scss" scoped>
.loading-icon {
  display: flex;
  align-items: center;
  font-size: 1rem;
  color: $rlt-second;
}
.loading-icon-tip {
  margin-left: 3px;
  font-size: 0.8rem;
}
.is-loading {
  .icon {
    animation: rl-rotate 2s linear infinite;
  }
}
@keyframes rl-rotate {
  0% {
    transform: rotate(0);
  }
  50% {
    transform: rotate(180deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
</style>
