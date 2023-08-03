<template>
  <div class="vm-skill">
    <div class="vm-skill-bar">
      <div class="vm-skill-list">
        <div class="vm-skill-item" v-for="(item, index) in skillList" :key="`vm-skill-item-${index}`">{{ item }}</div>
      </div>
      <div class="vm-skill-progress" ref="progress">
        <VMWave class="vm-skill-progress-inner" :height="innerH" />
      </div>
    </div>
    <div class="vm-skill-title">{{ title }}</div>
  </div>
</template>

<script setup lang="ts">
import { computed, onBeforeMount, onMounted, ref } from 'vue';
import VMWave from './VMWave.vue';

const len = ref('10%');
const progress = ref<HTMLElement>();
const progressH = ref(0);
const innerH = computed(() => {
  return progressH.value * (props.value / 100);
});

interface VMSkillProps {
  title?: string;
  skillList?: string[];
  value?: number;
}

const props = withDefaults(defineProps<VMSkillProps>(), {
  title: '境界',
  skillList: () => ['成仙', '渡劫', '大乘', '合体', '炼虚', '化神', '元婴', '结丹', '筑基', '练气'],
  value: 19
});

onBeforeMount(() => {
  len.value = `${props.skillList.length}%`;
});

onMounted(() => {
  const el = progress.value;
  if (el) {
    progressH.value = el.getBoundingClientRect().height;
  }
});
</script>

<style lang="less" scoped>
.vm-skill {
  user-select: none;
  width: 152px;
  height: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: space-between;
  box-sizing: border-box;
  padding: 12px;
  border-radius: 12px;
  transition: background-color 0.2s ease-in-out;
  background-color: var(--bg);
  box-shadow: -6px -6px 10px -1px var(--wshadow70), 6px 6px 10px -1px var(--bshadow15);
}
.vm-skill-bar {
  width: 100%;
  flex-grow: 1;
  display: flex;
  justify-content: space-between;
  column-gap: 12px;
  box-sizing: border-box;
  padding-top: 6px;
  .vm-skill-list {
    width: 36px;
    height: 100%;
    box-sizing: border-box;
    padding: 8px 0;
    display: flex;
    flex-direction: column;
    .vm-skill-item {
      text-align: center;
      max-width: 3em;
      height: v-bind(len);
      font-size: 14px;
      color: var(--primary-text);
      font-family: 'zcool-kuaile';
    }
  }
  .vm-skill-progress {
    position: relative;
    flex-grow: 1;
    border-radius: 8px;
    box-shadow: inset -2px -2px 6px 0 var(--wshadow70), inset 3px 3px 6px 0 var(--bshadow15);
    .vm-skill-progress-inner {
      position: absolute;
      bottom: 0;
    }
  }
}
.vm-skill-title {
  margin-top: 12px;
  font-family: 'YouSheBiaoTiHei';
  font-size: 24px;
  color: var(--primary-text);
}
</style>
