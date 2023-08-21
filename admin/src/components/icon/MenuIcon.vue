<template>
  <div class="menu-icon" :class="{ 'is-active': modelValue }" :style="mStyle" @click="click">{{ text }}</div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue';

interface MenuIconProps {
  modelValue?: boolean;
  text?: string;
  color?: string;
}
const props = withDefaults(defineProps<MenuIconProps>(), {
  modelValue: false,
  text: 'menu',
  color: undefined
});

const mStyle = computed(() => {
  const style: any = {};
  props.color && (style.color = props.color);
  return style;
});

const click = () => {
  emits('update:modelValue', !props.modelValue);
};

const emits = defineEmits(['update:modelValue']);
</script>

<style lang="scss" scoped>
.menu-icon {
  user-select: none;
  cursor: pointer;
  position: relative;
  overflow: hidden;
  transition: color 0.2s ease;
  color: $rlh-menuc;
  text-transform: uppercase;
  line-height: 3em;
  font-size: 0.7rem;
  &.is-active {
    color: transparent;
    &::before,
    &::after {
      transform: rotate(-45deg);
    }
  }
  &:before,
  &:after {
    content: '';
    position: absolute;
    transition: all 0.2s ease;
    box-sizing: border-box;
    width: 3em;
    height: 3em;
    left: 50%;
    margin: -0.2em -1.5em;
    border: 0.25em solid $rlh-menuc;
  }
  &:before {
    bottom: 100%;
    transform-origin: 85% 65%;
  }
  &:after {
    top: 100%;
    transform-origin: 17% 37%;
  }
}
</style>
