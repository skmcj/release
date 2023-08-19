<template>
  <div class="vm-work-item">
    <div class="vm-work-item-logo">
      <img v-if="logo" :src="logo" alt="logo" />
    </div>
    <div class="vm-work-item-content">
      <div class="vm-work-item-top">
        <div class="vm-work-item-title">
          <div class="text">{{ name }}</div>
          <div class="date-box">
            <span class="date">{{ date }}</span>
            <img v-if="stars" class="stars" :src="growStars(stars)" alt="stars" />
          </div>
        </div>
        <span class="vm-work-item-tip">{{ tip ?? '暂时没有相关介绍' }}</span>
      </div>
      <div class="vm-work-item-labels" v-if="labels && labels.length > 0">
        <div
          class="label"
          v-for="item of labels"
          :key="item.id"
          :class="{ disabled: !item.link }"
          @click.stop="clickLabel(item.link)"
          :style="{
            color: item.color
          }">
          <i v-if="item.icon" class="icon" :class="`ir-${item.icon}`"></i>
          <span class="tip" v-if="item.label">{{ item.label }}</span>
        </div>
        <!-- 介绍文章 -->
        <div
          v-if="!empty(article)"
          class="label"
          :key="article!.id"
          :class="{ disabled: !article?.path }"
          @click.stop="clickLabel(article?.path)"
          :style="{
            color: 'var(--article-ic)'
          }">
          <i class="icon ir-detail-line"></i>
          <span class="tip">简介</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import useTheme from '@/hooks/useTheme';
import { empty } from '@/utils/commonUtil';
import { validateStars } from '@/utils/validateUtil';
import type { ProductLabel, Article } from '@/api/indexApi';
interface VMWorkItemProps {
  name?: string;
  date?: string;
  tip?: string;
  logo?: string;
  labels?: ProductLabel[];
  stars?: string;
  article?: Article;
}

const props = withDefaults(defineProps<VMWorkItemProps>(), {
  name: '名称',
  date: '1970-01-01',
  tip: undefined,
  logo: undefined,
  labels: undefined,
  stars: undefined,
  article: undefined
});

const { theme } = useTheme();

// 点击label
const clickLabel = (link: string | undefined) => {
  if (!link) return;
  window.open(link, '_blank');
};

const growStars = (link: string) => {
  if (!validateStars(link)) return link;
  // https://img.shields.io/github/stars/skmcj/dycast?logo=github
  if (theme.value !== 'dark') {
    return `${link}?logo=github&logoColor=1f2328&labelColor=eaeef1&color=eaeef1`;
  } else {
    return `${link}?logo=github&logoColor=ffffff&labelColor=1d1e1f&color=1d1e1f`;
  }
};
</script>

<style lang="less" scoped>
.vm-work-item {
  width: 100%;
  border-radius: 12px;
  padding: 8px 16px;
  box-shadow: inset -3px -3px 6px 0px var(--wshadow70), inset 3px 3px 6px 0px var(--bshadow15);
  box-sizing: border-box;
  display: flex;
  align-items: center;
  font-family: 'zcool-kuaile';
  & + .vm-work-item {
    margin-top: 12px;
  }
}
.vm-work-item-logo {
  width: 96px;
  height: 96px;
  border-radius: 12px;
  padding: 12px;
  box-sizing: border-box;
  background-color: var(--bg);
  box-shadow: -3px -3px 6px 0px var(--wshadow70), 3px 3px 6px 0px var(--bshadow15);
  margin-right: 12px;
  flex-shrink: 0;
  img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
}
.vm-work-item-content {
  min-width: 0;
  height: 100%;
  flex-grow: 1;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}
.vm-work-item-top {
  width: 100%;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
}
.vm-work-item-title {
  width: 100%;
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  .text {
    box-sizing: border-box;
    padding: 8px 16px;
    border-radius: 8px;
    box-shadow: -3px -3px 6px 0px var(--wshadow70), 3px 3px 6px 0px var(--bshadow15);
    color: var(--primary-text);
    font-size: 18px;
    margin-top: 10px;
  }
  .date-box {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
  }
  .date {
    box-sizing: border-box;
    padding: 3px 6px;
    border-radius: 5px;
    box-shadow: inset -3px -3px 6px 0px var(--wshadow70), inset 3px 3px 6px 0px var(--bshadow15);
    color: var(--date-text);
    font-size: 12px;
  }
  .stars {
    margin-top: 6px;
    height: 1.5em;
    box-sizing: border-box;
    padding: 2px 5px;
    border-radius: 5px;
    box-shadow: inset -3px -3px 6px 0px var(--wshadow70), inset 3px 3px 6px 0px var(--bshadow15);
  }
}
.vm-work-item-tip {
  max-width: calc(100% - 8px);
  margin-top: 8px;
  margin-left: 8px;
  box-sizing: border-box;
  padding: 5px 8px;
  border-radius: 5px;
  font-size: 14px;
  color: var(--second-text);
  box-shadow: -3px -3px 6px 0px var(--wshadow70), 3px 3px 6px 0px var(--bshadow15);
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
.vm-work-item-labels {
  margin-left: 8px;
  margin-top: 12px;
  box-sizing: border-box;
  display: flex;
  gap: 12px 10px;
  flex-wrap: wrap;
  .label {
    box-sizing: border-box;
    display: flex;
    align-items: center;
    padding: 5px 8px;
    border-radius: 5px;
    color: var(--second-text);
    transition: box-shadow 0.2s linear, transform 0.1s linear;
    box-shadow: -3px -3px 6px 0px var(--wshadow70), 3px 3px 6px 0px var(--bshadow15);
    &:not(.disabled) {
      &:hover {
        box-shadow: -1px -1px 3px 0px var(--wshadow70), 1px 1px 3px 0px var(--bshadow15);
      }
      &:active {
        transform: translate(2px, 2px) scale(0.96);
        box-shadow: -0.5px -0.5px 0px 0px var(--wshadow0), 0px 2px 3px -10px var(--bshadow5),
          0.5px 0.5px 0px 0px var(--bshadow15), inset -4px -4px 6px -1px var(--wshadow70),
          inset 4px 4px 6px -1px var(--bshadow20);
      }
    }
    &.disabled {
    }
    .icon {
      & + .tip {
        margin-left: 6px;
      }
    }
  }
}
</style>
