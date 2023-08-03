<template>
  <div class="vm-lword-item">
    <div class="vm-lword-item-header">
      <span class="name">{{ nickname }}</span>
      <span class="date">{{ date }}</span>
    </div>
    <div class="vm-lword-item-content">{{ text }}</div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';

interface LWordItemProps {
  nickname?: string;
  date?: string;
  text?: string;
}
const props = withDefaults(defineProps<LWordItemProps>(), {
  nickname: '薛定谔的箱子',
  date: '1970-01-01',
  text: '什么也没留下~'
});
</script>

<style lang="less" scoped>
.vm-lword-item {
  width: 100%;
  display: flex;
  flex-direction: column;
  border-radius: 12px;
  transition: background-color 0.2s ease-in-out;
  background-color: var(--bg);
  box-shadow: -6px -6px 10px -1px var(--wshadow70), 6px 6px 10px -1px var(--bshadow15);
  box-sizing: border-box;
  padding: 6px 8px;
  flex-shrink: 0;
  & + .vm-lword-item {
    margin-top: 12px;
  }
}
.vm-lword-item-header {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  font-family: 'zcool-kuaile';
  .name {
    font-size: 12px;
    color: var(--primary-text);
  }
  .date {
    font-size: 10px;
    color: var(--date-text);
  }
}
.vm-lword-item-content {
  margin-top: 6px;
  font-family: 'zcool-kuaile';
  box-sizing: border-box;
  padding-left: 8px;
  font-size: 10px;
  color: var(--word-text);
}
</style>
