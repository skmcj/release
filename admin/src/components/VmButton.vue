<template>
  <div class="vm-button">
    <i v-if="iconClass" class="vm-button-icon" :class="iconClass"></i>
    <span v-if="$slots.default" class="vm-button-text">
      <slot />
    </span>
  </div>
</template>

<script setup lang="ts">
interface VMButtonProps {
  iconClass?: string;
}
const props = withDefaults(defineProps<VMButtonProps>(), {
  iconClass: undefined
});
</script>

<style lang="scss">
.vm-button {
  cursor: pointer;
  user-select: none;
  padding: 8px 15px;
  height: 32px;
  border-radius: 8px;
  box-sizing: border-box;
  background-color: $rlbg-default;
  transition: box-shadow 0.2s linear, transform 0.1s linear, background-color 0.2s ease-in-out;
  box-shadow: -6px -6px 10px -1px $wshadow70, 6px 6px 10px -1px $bshadow15;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  &:hover {
    box-shadow: -2px -2px 5px 0px $wshadow70, 2px 2px 5px 0px $bshadow15;
  }
  &:active {
    transform: translate(2px, 2px scale(0.9));
    box-shadow: -0.5px -0.5px 0px 0px $wshadow0, 0px 2px 3px -10px $bshadow5, 0.5px 0.5px 0px 0px $bshadow15,
      inset -4px -4px 6px -1px $wshadow70, inset 4px 4px 6px -1px $bshadow20;
  }
  color: $rlt-title;
  .vm-button-icon {
    font-size: 1.2rem;
    & + .vm-button-text {
      margin-left: 6px;
    }
  }
  .vm-button-text {
    font-size: 16px;
  }
}
</style>
