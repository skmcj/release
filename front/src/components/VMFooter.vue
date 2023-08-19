<template>
  <div class="vm-footer" :class="{ 'vm-footer_small': size === 'small' }">
    <i class="icon ir-copyright-line"></i>
    <span class="year">{{ year }}</span>
    <span class="author">{{ author }}</span>
    <span class="tip" v-if="day">{{ `本站已稳定运行 ${day} 天` }}</span>
  </div>
</template>

<script setup lang="ts">
interface VMFooterProps {
  day?: number;
  year?: number;
  author?: string;
  size?: string;
}
const props = withDefaults(defineProps<VMFooterProps>(), {
  day: undefined,
  year: 1970,
  author: 'SKMCJ',
  size: 'default'
});
</script>

<style lang="less" scoped>
.vm-footer {
  user-select: none;
  display: flex;
  align-items: center;
  border-radius: 12px;
  padding: 8px 24px;
  box-sizing: border-box;
  color: var(--primary-text);
  font-family: 'Source Han Sans CN';
  background-color: var(--bg);
  box-shadow: -6px -6px 10px -1px var(--wshadow70), 6px 6px 10px -1px var(--bshadow15);
  &.vm-footer_small {
    .icon {
      font-size: 16px;
    }
    .year {
      font-size: 14px;
    }
    .tip {
      font-size: 12px;
    }
  }
  .icon {
    font-size: 18px;
  }
  .year {
    margin-left: 8px;
    font-size: 16px;
    font-weight: bold;
  }
  .author {
    margin-left: 9px;
    font-family: 'zcool-kuaile';
    font-size: 18px;
    background: var(--author-text);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    text-fill-color: transparent;
  }
  .tip {
    margin-left: 27px;
    font-size: 14px;
  }
}
</style>
