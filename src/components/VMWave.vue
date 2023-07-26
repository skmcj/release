<template>
  <div class="vm-wave" :style="wStyle">
    <svg
      version="1.1"
      id="wave"
      class="vm-wave-svg"
      :style="sStyle"
      shape-rendering="auto"
      xmlns="http://www.w3.org/2000/svg"
      xmlns:xlink="http://www.w3.org/1999/xlink"
      :viewBox="`0 0 64 ${height}`"
      xml:space="preserve">
      <defs>
        <filter
          id="wave-shadow"
          filterUnits="objectBoundingBox"
          color-interpolation-filters="sRGB"
          x="-23"
          y="-23"
          width="109"
          height="113.50000762939453">
          <feFlood flood-opacity="0" result="BackgroundImageFix" />
          <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" />
          <feOffset dy="-2" dx="-2" />
          <feGaussianBlur stdDeviation="3" />
          <feColorMatrix type="matrix" values="0 0 0 0 1 0 0 0 0 1 0 0 0 0 1 0 0 0 0.7 0" />
          <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow" />
          <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" />
          <feOffset dy="3" dx="3" />
          <feGaussianBlur stdDeviation="3" />
          <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.16 0" />
          <feBlend mode="normal" in2="effect1_dropShadow" result="effect2_dropShadow" />
          <feBlend mode="normal" in="SourceGraphic" in2="effect2_dropShadow" result="shape" />
        </filter>
      </defs>
      <g>
        <path
          id="gentle-wave"
          class="vm-wave-item"
          d="M-113.2,18c30,0,58-18,88-18s58,18,88,18s58-18,88-18s58,18,88,18v5h-352V18z" />
        <rect y="22" width="64" height="100%" fill="var(--wave-bg)" />
      </g>
    </svg>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { getPropsStyle } from '@/utils/commonUtil';

interface VMWaveProps {
  width?: string;
  height?: number | string;
  radius?: number | string;
}

const props = withDefaults(defineProps<VMWaveProps>(), {
  width: undefined,
  height: 72,
  radius: undefined
});

const wStyle = computed(() => {
  const style: any = {};
  props.width && (style.width = getPropsStyle(props.width));
  return style;
});

const sStyle = computed(() => {
  const style: any = {};
  props.radius && (style.borderRadius = getPropsStyle(props.radius));
  return style;
});
</script>

<style lang="less" scoped>
.vm-wave {
  width: 100%;
  box-sizing: border-box;
  padding: 0 3px;
  overflow: hidden;
  filter: drop-shadow(-5px -5px 12px var(--wshadow70)) drop-shadow(5px 5px 12px var(--bshadow15));
}
.vm-wave-svg {
  border-radius: 8px;
  width: 100%;
  // overflow: hidden;
}
.vm-wave-item {
  -webkit-animation: wave 12s cubic-bezier(0.55, 0.5, 0.45, 0.5) infinite;
  animation: wave 12s cubic-bezier(0.55, 0.5, 0.45, 0.5) infinite;
  fill: var(--wave-bg);
}

@keyframes wave {
  0% {
    transform: translate3d(-90px, 0, 0);
  }

  100% {
    transform: translate3d(85px, 0, 0);
  }
}
</style>
