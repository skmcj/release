<!-- 今日运势 -->
<template>
  <div class="vm-fate" ref="fateBox" :class="{ 'is-vertical': direction === 'vertical' }">
    <div class="vm-fate-box" :style="fStyle" ref="fateInner">
      <div class="vm-fate-title">
        <span class="text">今日运势</span>
        <VMStarSignSelect class="select" :select-list="selectList" v-model="selectValue" type="icon" />
      </div>
      <div class="vm-fate-inner">
        <VMProgress
          label="爱情"
          :percentage="qlove"
          :direction="direction"
          :c-deg="direction === 'vertical' ? 90 : 180" />
        <VMProgress
          label="事业"
          :percentage="qwork"
          :direction="direction"
          :c-deg="direction === 'vertical' ? 90 : 180" />
        <VMProgress
          label="财富"
          :percentage="qmoney"
          :direction="direction"
          :c-deg="direction === 'vertical' ? 90 : 180" />
        <VMProgress
          label="健康"
          :percentage="qhealth"
          :direction="direction"
          :c-deg="direction === 'vertical' ? 90 : 180" />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onBeforeMount, watch } from 'vue';
import { getPropsStyle } from '@/utils/commonUtil';
import VMStarSignSelect from './VMStarSignSelect.vue';
import VMProgress from './VMProgress.vue';
import type { SelectItem } from '@/utils/commonType';
import { getHoroscopeApi } from '@/api/otherApi';

const selectList: SelectItem[] = [
  { type: 'icon', label: '水瓶座', value: 'aquarius', iconClass: 'ir-aquarius-line' },
  { type: 'icon', label: '双鱼座', value: 'pisces', iconClass: 'ir-pisces-line' },
  { type: 'icon', label: '白羊座', value: 'aries', iconClass: 'ir-aries-line' },
  { type: 'icon', label: '金牛座', value: 'taurus', iconClass: 'ir-taurus-line' },
  { type: 'icon', label: '双子座', value: 'gemini', iconClass: 'ir-gemini-line' },
  { type: 'icon', label: '巨蟹座', value: 'cancer', iconClass: 'ir-cacer-line' },
  { type: 'icon', label: '狮子座', value: 'leo', iconClass: 'ir-leo-line' },
  { type: 'icon', label: '处女座', value: 'virgo', iconClass: 'ir-virgo-line' },
  { type: 'icon', label: '天秤座', value: 'libra', iconClass: 'ir-libra-line' },
  { type: 'icon', label: '天蝎座', value: 'scorpio', iconClass: 'ir-scorpio-line' },
  { type: 'icon', label: '射手座', value: 'sagittarius', iconClass: 'ir-sagittarius-line' },
  { type: 'icon', label: '摩羯座', value: 'capricorn', iconClass: 'ir-capricom-line' }
];
const selectValue = ref('pisces');

const fateBox = ref<HTMLElement>();
const fateInner = ref<HTMLElement>();

// 各运势
const qlove = ref(0);
const qwork = ref(0);
const qmoney = ref(0);
const qhealth = ref(0);

interface VMfateProps {
  width?: number | string;
  height?: number | string;
  radius?: number | string;
  scale?: number;
  bg?: boolean;
  direction?: string;
}
const props = withDefaults(defineProps<VMfateProps>(), {
  width: undefined,
  height: undefined,
  radius: undefined,
  scale: undefined,
  bg: true,
  direction: 'horizontal'
});

const fStyle = computed(() => {
  const style: any = {};
  if (props.width && props.height) {
    style.width = getPropsStyle(props.width);
    style.height = getPropsStyle(props.height);
  }
  props.radius && (style.borderRadius = getPropsStyle(props.radius));
  caleScale();
  return style;
});

/**
 * 计算缩放比例
 */
function caleScale() {
  if (!fateInner.value || !fateBox.value) return;
  const boxEl = fateBox.value;
  const innerEl = fateInner.value;
  let scale = 1;
  if (props.width && !props.height) {
    scale = getSize(props.width, 'w');
  } else if (!props.width && props.height) {
    scale = getSize(props.height);
  } else if (props.scale) {
    scale = props.scale;
  }
  if (scale !== 1) {
    innerEl.style.transform = `scale(${scale})`;
    innerEl.style.transformOrigin = 'left top';
    let innerRect = innerEl.getBoundingClientRect();
    boxEl.style.width = `${innerRect.width}px`;
    boxEl.style.height = `${innerRect.height}px`;
  }
}

// 获取对应尺码
function getSize(val: number | string, type: string = 'h') {
  if (typeof val === 'string') {
    val = parseInt(val.replace(/[a-z]/g, ''));
  }
  // w : h = 290 : 162
  let size = 0;
  switch (type) {
    case 'w':
      size = val / 290;
      break;
    case 'h':
      size = val / 162;
      break;
  }
  return size;
}

onBeforeMount(() => {
  getHoroscope(selectValue.value);
});

watch(selectValue, val => {
  getHoroscope(val);
});

/**
 * 获取运势
 */
function getHoroscope(ss: string) {
  getHoroscopeApi(ss)
    .then(res => {
      const data = res.data.data.index;
      qlove.value = getPVal(data.love);
      qwork.value = getPVal(data.work);
      qmoney.value = getPVal(data.money);
      qhealth.value = getPVal(data.health);
    })
    .catch(err => {});
}

// 获取百分比字符串数值
function getPVal(p: string) {
  return parseInt(p.slice(0, -1));
}
</script>

<style lang="less" scoped>
.vm-fate {
  user-select: none;
  border-radius: 12px;
  transition: background-color 0.2s ease-in-out;
  background-color: var(--bg);
  box-shadow: -6px -6px 10px -1px var(--wshadow70), 6px 6px 10px -1px var(--bshadow15);
  &.is-vertical {
    .vm-fate-inner {
      flex-direction: row;
    }
  }
  .vm-fate-box {
    overflow: hidden;
    width: 290px;
    height: 162px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-direction: column;
    box-sizing: border-box;
    padding: 8px;
  }
  .vm-fate-title {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    width: 100%;
    margin-bottom: 5px;
    .text {
      font-size: 24px;
      font-family: 'YouSheBiaoTiHei';
      color: var(--primary-text);
    }
    .select {
      margin-right: 12px;
      margin-bottom: 5px;
    }
  }
  .vm-fate-inner {
    position: relative;
    width: 100%;
    height: 100%;
    border-radius: 12px;
    box-shadow: inset -3px -3px 6px 0px var(--wshadow70), inset 3px 3px 6px 0px var(--bshadow15);
    box-sizing: border-box;
    padding: 8px 16px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }
}
</style>
