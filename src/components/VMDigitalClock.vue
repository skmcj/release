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
import { getPropsStyle } from '@/utils/commonUtil';

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
  // 开发阶段暂时关闭
  // dclock();
});

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

interface DateFormatMap {
  [key: string]: number | string;
}

/**
 *
 * @param date
 * @param format
 */
function formatDate(date: Date, format: string = 'HH:mm') {
  const re = /(y+)/;
  if (re.test(format)) {
    const t = re.exec(format)![1];
    format = format.replace(t, (date.getFullYear() + '').substring(4 - t.length));
  }

  const o: DateFormatMap = {
    'M+': date.getMonth() + 1, // 月
    'd+': date.getDate(), // 日
    'h+': date.getHours() % 12 === 0 ? 12 : date.getHours() % 12, // 小时[12]
    'H+': date.getHours(), // 小时[24]
    'm+': date.getMinutes(), // 分
    's+': date.getSeconds(), // 秒
    'q+': Math.floor((date.getMonth() + 3) / 3), // 季度
    S: date.getMilliseconds(), // 毫秒
    a: date.getHours() < 12 ? '上午' : '下午', // 上午/下午
    A: date.getHours() < 12 ? 'AM' : 'PM' // AM/PM
  };
  for (let k in o) {
    const regx = new RegExp('(' + k + ')');
    if (regx.test(format)) {
      const t = regx.exec(format)![1];
      format = format.replace(t, t.length === 1 ? `${o[k]}` : `00${o[k]}`.slice(t.length * -1));
    }
  }
  return format;
}
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
