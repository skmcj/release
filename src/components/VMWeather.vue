<template>
  <div class="vm-weather" :style="wStyle">
    <div class="vm-weather-inner">
      <template v-if="weather">
        <div v-if="bg" class="vm-weather-bg">
          <img src="@/assets/images/cat.gif" />
        </div>
        <!-- 天气信息 -->
        <span class="vm-weather_city">{{ weather.city }}</span>
        <span class="vm-weather_t">{{ `${getTVal(weather.info.low)}℃~${getTVal(weather.info.high)}℃` }}</span>
        <span class="vm-weather_w">{{ `${weather.info.type}·${weather.info.fengxiang}` }}</span>
        <span class="vm-weather_air">
          <i class="icon ir-air"></i>
          <span class="text" :style="getAirColor(weather.info.air.aqi_level)">{{ weather.info.air.aqi_name }}</span>
        </span>
      </template>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, onBeforeMount, ref } from 'vue';
import { getPropsStyle } from '@/utils/commonUtil';
import { getWeatherApi } from '@/api/otherApi';

const weather = ref();

// 获取天气
onBeforeMount(() => {
  getWeatherApi()
    .then(res => {
      weather.value = res.data;
    })
    .catch(err => {});
});

interface VMWeatherProps {
  width?: number | string;
  height?: number | string;
  radius?: number | string;
  scale?: number;
  bg?: boolean;
}
const props = withDefaults(defineProps<VMWeatherProps>(), {
  width: undefined,
  height: undefined,
  radius: undefined,
  scale: 1,
  bg: true
});

const wStyle = computed(() => {
  const style: any = {};
  if (props.width && !props.height) {
    style.transform = `scale(${getSize(props.width, 'w')})`;
  } else if (!props.width && props.height) {
    style.transform = `scale(${getSize(props.height)})`;
  } else if (props.width && props.height) {
    style.width = getPropsStyle(props.width);
    style.height = getPropsStyle(props.height);
  }
  props.radius && (style.borderRadius = getPropsStyle(props.radius));
  props.scale !== 1 && (style.transform = `scale(${props.scale})`);
  return style;
});

// 获取对应尺码
function getSize(val: number | string, type: string = 'h') {
  if (typeof val === 'string') {
    val = parseInt(val.replace(/[a-z]/g, ''));
  }
  // w : h = 240 : 162
  let size = 0;
  switch (type) {
    case 'w':
      size = val / 240;
      break;
    case 'h':
      size = val / 162;
      break;
  }
  return size;
}

// 获取空气颜色
function getAirColor(lev: number = 1) {
  let style: any = {};
  style.color = `var(--air-${lev})`;
  return style;
}

// 获取温度数字
function getTVal(t: string) {
  const reg = /[^0-9]/g;
  return t.replace(reg, '');
}
</script>

<style lang="less" scoped>
.vm-weather {
  width: 240px;
  height: 162px;
  user-select: none;
  border-radius: 12px;
  transition: background-color 0.2s ease-in-out;
  background-color: var(--bg);
  display: flex;
  align-items: center;
  justify-content: center;
  box-sizing: border-box;
  padding: 8px;
  box-shadow: -6px -6px 10px -1px var(--wshadow70), 6px 6px 10px -1px var(--bshadow15);
  .vm-weather-inner {
    position: relative;
    overflow: hidden;
    width: 100%;
    height: 100%;
    border-radius: 12px;
    box-shadow: inset -3px -3px 6px 0px var(--wshadow70), inset 3px 3px 6px 0px var(--bshadow15);
    box-sizing: border-box;
    padding: 8px 12px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }
  .vm-weather_city {
    font-family: 'YouSheBiaoTiHei';
    font-size: 32px;
    color: var(--city-text);
  }
  .vm-weather_t {
    font-size: 14px;
    color: var(--primary-text);
  }
  .vm-weather_w {
    font-family: 'zcool-kuaile';
    font-size: 18px;
    color: var(--weather-text);
  }
  .vm-weather_air {
    font-size: 16px;
    color: var(--primary-text);
    .icon {
      color: var(--air-text);
    }
    .text {
      margin-left: 8px;
      font-family: 'zcool-kuaile';
    }
  }
  .vm-weather-bg {
    height: 50%;
    position: absolute;
    bottom: 12px;
    right: 8px;
    img {
      height: 100%;
      object-fit: cover;
    }
  }
}
</style>
