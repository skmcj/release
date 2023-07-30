<template>
  <div class="vm-work-card-item" :style="iStyle">
    <Transition name="vm-hidein" mode="out-in">
      <!-- 简介 -->
      <div class="vm-work-card-front" v-if="!isMore">
        <div class="logo">
          <img v-if="logo" :src="logo" alt="logo" />
        </div>
        <div class="name">
          <span>{{ name }}</span>
        </div>
      </div>
      <!-- 详情 -->
      <div class="vm-work-card-behind" v-else>
        <div class="top">
          <div class="logo">
            <img v-if="logo" :src="logo" alt="logo" />
          </div>
          <div class="mess">
            <span class="name">{{ name }}</span>
            <span class="date">{{ date }}</span>
          </div>
        </div>
        <div class="tip">{{ tip ?? '暂时没有相关介绍' }}</div>
        <div class="labels" v-if="labels && labels.length > 0">
          <div
            class="label"
            v-for="(item, index) in labels"
            :key="`vm-h5work-label-${name}-${index}`"
            :class="{ disabled: !item.link }"
            @click.stop="clickLabel(item.link)"
            :style="{
              color: item.color
            }">
            <i v-if="item.icon" :class="`ir-${item.icon}`"></i>
          </div>
        </div>
      </div>
    </Transition>
    <div class="vm-work-card-btn" @click.stop="clickMore">
      <span class="text">{{ isMore ? 'RETURN' : 'MORE' }}</span>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue';
import { getPropsStyle } from '@/utils/commonUtil';
import type { WorkLabel } from '@/utils/commonType';

const isMore = ref(false);

const clickMore = () => {
  isMore.value = !isMore.value;
};

// 点击label
const clickLabel = (link: string | undefined) => {
  if (!link) return;
  window.open(link, '_blank');
};

interface VMBlockProps {
  width?: number | string;
  height?: number | string;
  radius?: number | string;
  name?: string;
  date?: string;
  tip?: string;
  logo?: string;
  labels?: WorkLabel[];
}
const props = withDefaults(defineProps<VMBlockProps>(), {
  width: undefined,
  height: undefined,
  radius: undefined,
  name: '名称',
  date: '1970-01-01',
  tip: undefined,
  logo: undefined,
  labels: undefined
});

const iStyle = computed(() => {
  const style: any = {};
  props.width && (style.width = getPropsStyle(props.width));
  props.height && (style.height = getPropsStyle(props.height));
  props.radius && (style.borderRadius = getPropsStyle(props.radius));
  return style;
});
</script>

<style lang="less" scoped>
.vm-work-card-item {
  font-family: 'zcool-kuaile';
  font-size: 16px;
  width: 155px;
  height: 200px;
  box-sizing: border-box;
  border-radius: 12px;
  box-shadow: -0.5px -0.5px 1px 0px var(--wshadow4), 0.5px 0.5px 1px 0px var(--bshadow15),
    inset 3px 3px 6px 0px var(--wshadow1), inset -3px -3px 6px 0px var(--wshadow0);
  display: flex;
  flex-direction: column;
  align-items: center;
  overflow: hidden;
  perspective: 100px;
}

.vm-work-card-front,
.vm-work-card-behind {
  width: 100%;
  flex-grow: 1;
  display: flex;
  align-items: center;
  flex-direction: column;
  transform-style: preserve-3d;
}
.vm-work-card-front {
  justify-content: center;
  .logo {
    width: 108px;
    height: 108px;
    border-radius: 12px;
    box-sizing: border-box;
    padding: 18px;
    box-shadow: 3px 3px 6px 0px var(--wshadow1), -3px -3px 6px 0px var(--wshadow0);
    img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
  }
  .name {
    box-sizing: border-box;
    padding: 0 12px;
    height: 24px;
    line-height: 24px;
    text-align: center;
    border-radius: 12px;
    box-shadow: inset 3px 3px 6px 0px var(--wshadow1), inset -3px -3px 6px 0px var(--wshadow0);
    font-size: 16px;
    color: var(--primary-text);
    margin-top: 8px;
  }
}

.vm-work-card-behind {
  justify-content: space-between;
  box-sizing: border-box;
  padding: 8px 12px;
  .top {
    display: flex;
    align-items: center;
    .logo {
      margin-right: 8px;
      width: 3em;
      height: 3em;
      border-radius: 12px;
      box-sizing: border-box;
      padding: 8px;
      box-shadow: 3px 3px 6px 0px var(--wshadow1), -3px -3px 6px 0px var(--wshadow0);
      flex-shrink: 0;
      img {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }
    }
    .mess {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      justify-content: space-around;
      .name {
        font-size: 14px;
        color: var(--primary-text);
      }
      .date {
        margin-top: 8px;
        font-size: 10px;
        color: var(--date-text);
      }
      .name,
      .date {
        box-sizing: border-box;
        padding: 3px 5px;
        border-radius: 5px;
        box-shadow: 3px 3px 6px 0px var(--wshadow1), -3px -3px 6px 0px var(--wshadow0);
      }
    }
  }
  .tip {
    font-size: 12px;
    color: var(--second-text);
    box-shadow: 3px 3px 6px 0px var(--wshadow1), -3px -3px 6px 0px var(--wshadow0);
    border-radius: 5px;
    box-sizing: border-box;
    padding: 3px 5px;
  }
  .labels {
    display: flex;
    align-items: center;
    justify-content: center;
    .label {
      font-size: 12px;
      box-sizing: border-box;
      padding: 3px;
      border-radius: 5px;
      transition: box-shadow 0.2s linear, transform 0.1s linear;
      box-shadow: 3px 3px 6px 0px var(--wshadow1), -3px -3px 6px 0px var(--wshadow0);
      color: var(--second-text);
      & + .label {
        margin-left: 6px;
      }
      &:not(.disabled) {
        &:active {
          transform: translate(2px, 2px) scale(0.96);
          box-shadow: -0.5px -0.5px 0px 0px var(--wshadow0), 0px 2px 3px -10px var(--bshadow5),
            0.5px 0.5px 0px 0px var(--bshadow15), inset -4px -4px 6px -1px var(--wshadow70),
            inset 4px 4px 6px -1px var(--bshadow20);
        }
      }
    }
  }
}
.vm-work-card-btn {
  font-size: 16px;
  width: 100%;
  height: 16%;
  background-color: var(--bg);
  box-shadow: -3px -3px 6px 0px var(--wshadow0);
  transition: box-shadow 0.2s linear;
  color: var(--primary-text);
  display: flex;
  align-items: center;
  justify-content: center;
  .text {
    transition: transform 0.1s linear;
  }
  &:active {
    .text {
      transform: translateY(-1.5px) scale(0.98);
    }
    box-shadow: inset 3px -3px 6px 0px var(--wshadow1), inset -3px -3px 6px 0px var(--wshadow0);
  }
}
</style>
