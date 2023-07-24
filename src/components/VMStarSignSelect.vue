<template>
  <div class="vm-star-sign-select" ref="selectBox" @click.stop="showSelect">
    <!-- 输入框 -->
    <div class="vm-ss-select-input">
      <span class="text" v-if="type !== 'icon'">{{ selectText }}</span>
      <i class="icon" v-if="type === 'icon'" :class="selectText"></i>
    </div>
    <!-- 下拉栏 -->
    <VMPopper :inset="`${dwTop}px auto auto ${dwLeft}px`" v-model="selectFlag" :duration="1500">
      <div class="vm-ss-select-list" v-if="selectList && selectList.length > 0">
        <div
          class="vm-ss-select-item show-title"
          v-for="(item, index) of selectList"
          :key="`vm-ss-select-it-${index}`"
          :class="{
            'is-active': modelValue == item.value
          }"
          @click.stop="select(item)">
          <template v-if="item.type === 'string'">
            <span class="label">{{ item.label }}</span>
            <span class="value" v-if="showValue">{{ item.value }}</span>
          </template>
          <template v-if="item.type === 'icon'">
            <span class="label">{{ item.label }}</span>
            <i class="icon" :class="`${item.iconClass}`"></i>
            <span class="value" v-if="showValue">{{ item.value }}</span>
          </template>
        </div>
      </div>
    </VMPopper>
  </div>
</template>

<script setup lang="ts">
import { onBeforeMount, onMounted, onUpdated, ref } from 'vue';
import VMPopper from './VMPopper.vue';
import type { SelectItem } from '@/utils/commonType';

const selectBox = ref<HTMLElement>();

const selectFlag = ref(false);
const dwTop = ref(0);
const dwLeft = ref(0);

const selectText = ref('');

interface VMStarSignSelectProps {
  modelValue?: string | number;
  tip?: string;
  selectList?: SelectItem[];
  showValue?: boolean;
  type?: string; // icon | label
}

const props = withDefaults(defineProps<VMStarSignSelectProps>(), {
  modelValue: undefined,
  tip: undefined,
  selectList: undefined,
  showValue: false,
  type: 'label'
});

onBeforeMount(() => {
  findShowText(props.modelValue, props.type);
});

onMounted(() => {
  refreshSelectBox();
});

onUpdated(() => {
  refreshSelectBox();
});

function refreshSelectBox() {
  const el = selectBox.value;
  if (el) {
    const rect = el.getBoundingClientRect();
    dwTop.value = rect.bottom + 6 + window.scrollY;
    dwLeft.value = rect.left + 8 + window.scrollX;
  }
}

function showSelect() {
  selectFlag.value = true;
}

function findShowText(val: string | number | undefined, type: string = 'label') {
  if (!props.selectList) return;
  if (type !== 'icon') {
    for (const item of props.selectList) {
      if (item.value === val) {
        selectText.value = item.label;
        break;
      }
    }
  } else {
    for (const item of props.selectList) {
      if (item.value === val) {
        selectText.value = item.iconClass as string;
        break;
      }
    }
  }
}

function select(item: SelectItem) {
  emits('update:modelValue', item.value);
  findShowText(item.value, props.type);
}

const emits = defineEmits(['update:modelValue']);
</script>

<style lang="less" scoped>
.vm-star-sign-select {
  position: relative;
  user-select: none;
  .vm-ss-select-input {
    font-family: 'zcool-kuaile';
    font-size: 12px;
    color: var(--light-gray);
    .text {
    }
    .tip {
      opacity: 0.8;
    }
  }
}
</style>
