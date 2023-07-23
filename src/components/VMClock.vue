<template>
  <div class="vm-clock" :style="cStyle">
    <div class="vm-clock-marker">
      <div class="vm-clock_inner">
        <div class="hand hours" ref="hours"></div>
        <div class="hand minutes" ref="minutes"></div>
        <div class="hand seconds" ref="seconds"></div>
        <div class="point"></div>
      </div>
      <span class="vm-clock-marker_1"></span>
      <span class="vm-clock-marker_2"></span>
      <span class="vm-clock-marker_3"></span>
      <span class="vm-clock-marker_4"></span>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';

// 各个时针
const hours = ref<HTMLDivElement>();
const minutes = ref<HTMLDivElement>();
const seconds = ref<HTMLDivElement>();

interface VMClockProps {
  size?: number | string;
}
const props = withDefaults(defineProps<VMClockProps>(), {
  size: undefined
});

const cStyle = computed(() => {
  const style: any = {};
  props.size && (style.width = getPropsStyle(props.size));
  props.size && (style.height = getPropsStyle(props.size));
  return style;
});
const getPropsStyle = (val: number | string) => {
  if (typeof val === 'number') return `${val}px`;
  else return val;
};

onMounted(() => {
  // 开发阶段暂时关闭
  // clock();
});

// 控制时钟转动
const clock = () => {
  let today = new Date();
  let h = (today.getHours() % 12) + today.getMinutes() / 59; // 22 % 12 = 10pm
  let m = today.getMinutes(); // 0 - 59
  let s = today.getSeconds(); // 0 - 59

  h *= 30;
  m *= 6;
  s *= 6;

  rotation(hours.value as HTMLDivElement, h);
  rotation(minutes.value as HTMLDivElement, m);
  rotation(seconds.value as HTMLDivElement, s);

  // 每秒调用一次
  setTimeout(clock, 500);
};

// 设置转动角度
const rotation = (target: HTMLElement, val: number) => {
  target.style.transform = `rotate(${val}deg)`;
};
</script>

<style lang="less" scoped>
.vm-clock {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 120px;
  height: 120px;
  border-radius: 50%;
  background-color: var(--bg);
  box-shadow: 4px 4px 8px 0px var(--wshadow3), -2px -2px 6px 0px var(--wshadow0);
}
.vm-clock-marker {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 95%;
  height: 95%;
  border-radius: 50%;
  box-shadow: inset 2px 2px 6px 0px var(--wshadow3), inset -2px -2px 6px 0px var(--wshadow4);
  &::after {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    content: '';
    display: block;
    width: 55%;
    height: 55%;
    border-radius: 50%;
    box-shadow: inset 1px 1px 1px 0px var(--wshadow3), inset -1px -1px 1px 0px var(--wshadow4);
  }
}
.vm-clock_inner {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  .point {
    width: 8%;
    height: 8%;
    border-radius: 50%;
    background-color: var(--point);
    z-index: 10;
  }
  .hand {
    position: absolute;
    transform-origin: bottom;
    bottom: 50%;
  }
  .hours {
    z-index: 7;
    width: 6px;
    height: 24%;
    border-radius: 3px;
    background-color: var(--hours);
  }
  .minutes {
    z-index: 8;
    width: 4px;
    height: 36%;
    border-radius: 2px;
    background-color: var(--minutes);
  }
  .seconds {
    z-index: 9;
    width: 3px;
    height: 44%;
    border-radius: 1.5px;
    background-color: var(--seconds);
  }
}
.vm-clock-marker_1,
.vm-clock-marker_2,
.vm-clock-marker_3,
.vm-clock-marker_4 {
  position: absolute;
  border-radius: 1.5px;
  background-color: var(--marker-bg);
  box-shadow: -1px -1px 1px 0px var(--wshadow4), inset 1px 1px 1px 0px var(--wshadow3);
}
.vm-clock-marker_1,
.vm-clock-marker_3 {
  width: 3px;
  height: 12px;
}
.vm-clock-marker_2,
.vm-clock-marker_4 {
  width: 12px;
  height: 3px;
}

.vm-clock-marker_1 {
  top: 5px;
}
.vm-clock-marker_2 {
  right: 5px;
}
.vm-clock-marker_3 {
  bottom: 5px;
}
.vm-clock-marker_4 {
  left: 5px;
}
</style>
