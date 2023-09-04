<template>
  <div
    class="vm-dclock"
    :class="{
      'is-in': props.in
    }"
    :style="bStyle">
    <span class="vm-time">{{ time }}</span>
  </div>
</template>

<script setup lang="ts">
import { computed, onBeforeMount, ref } from 'vue';
import { getPropsStyle, formatDate } from '@/utils/commonUtil';

const time = ref('00:00');

interface VMBlockProps {
  in?: boolean;
  width?: number | string;
  height?: number | string;
  radius?: number | string;
  padding?: number | string;
  fontSize?: number | string;
  ping?: string;
  format?: string; // 显示类型: [h-时, m-分, s-秒]
}
const props = withDefaults(defineProps<VMBlockProps>(), {
  in: false,
  width: undefined,
  height: undefined,
  radius: undefined,
  fontSize: undefined,
  /**
   * 每隔多久更新一次
   * h-1时, m-1分, s-1秒
   */
  ping: 'm',
  /** 日期时间格式字符串
   * y-年, M-月, d-日, h-时12, H-时24, m-分, s-秒, S-毫秒, a-上午|下午, A-AM|PM
   */
  format: 'HH:mm'
});

const bStyle = computed(() => {
  const style: any = {};
  props.width && (style.width = getPropsStyle(props.width));
  props.height && (style.height = getPropsStyle(props.height));
  props.radius && (style.borderRadius = getPropsStyle(props.radius));
  props.padding && (style.padding = getPropsStyle(props.padding));
  props.fontSize && (style.fontSize = getPropsStyle(props.fontSize));
  return style;
});

onBeforeMount(() => {
  calClock();
  // dclock();
});

// 校准时间
const calClock = () => {
  const now = new Date();
  const pre = new Date();
  time.value = formatDate(now, props.format);
  let next = null;
  switch (props.ping) {
    case 'h':
      pre.setHours(pre.getHours() + 1);
      next = new Date(formatDate(pre, 'yyyy-MM-dd HH'));
      setTimeout(dclock, next.getTime() - now.getTime());
      break;
    case 'm':
      pre.setMinutes(pre.getMinutes() + 1);
      next = new Date(formatDate(pre, 'yyyy-MM-dd HH:mm'));
      setTimeout(dclock, next.getTime() - now.getTime());
      break;
    case 's':
      setTimeout(dclock, 500);
      break;
  }
};

/**
 * 控制时钟运行
 */
const dclock = () => {
  time.value = formatDate(new Date(), props.format);
  switch (props.ping) {
    case 'h':
      setTimeout(dclock, 3599700);
      break;
    case 'm':
      setTimeout(dclock, 59700);
      break;
    case 's':
      setTimeout(dclock, 500);
      break;
  }
};
</script>

<style lang="less" scoped>
.vm-dclock {
  user-select: none;
  border-radius: 12px;
  transition: background-color 0.2s ease-in-out;
  background-color: var(--bg);
  display: flex;
  align-items: center;
  justify-content: center;
  box-sizing: border-box;
  padding: 6px 12px;
  font-family: 'YouSheBiaoTiHei';
  &:not(.is-in) {
    box-shadow: -6px -6px 10px -1px var(--wshadow70), 6px 6px 10px -1px var(--bshadow15);
  }
  &.is-in {
    box-shadow: inset 2px 2px 3px 0px var(--wshadow1), inset -2px -2px 3px 0px var(--wshadow0);
  }
  .vm-time {
    font-size: 30px;
    color: var(--primary-text);
  }
}
</style>
