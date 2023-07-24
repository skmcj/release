<template>
  <div
    class="vm-sentence"
    :class="{
      'is-in': props.in
    }">
    <i class="icon ir-dir show-title" data-title="点击复制" @click.stop="copy"></i>
    <span class="text">{{ message }}</span>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import EsMessage from '@/components/EasyMessage';
import { copyToClipboard } from '@/utils/commonUtil';

interface VMSentenceProps {
  in?: boolean;
  message?: string;
}
const props = withDefaults(defineProps<VMSentenceProps>(), {
  in: false,
  message: ''
});

const copy = () => {
  copyToClipboard(props.message)
    .then(() => {
      EsMessage({ message: '复制成功' });
    })
    .catch(err => {
      EsMessage({ message: '复制失败' });
    });
};
</script>

<style lang="less" scoped>
.vm-sentence {
  user-select: none;
  width: 100%;
  box-sizing: border-box;
  padding: 0 24px;
  height: 42px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  &:not(.is-in) {
    box-shadow: -6px -6px 10px -1px var(--wshadow70), 6px 6px 10px -1px var(--bshadow15);
  }
  &.is-in {
    box-shadow: inset 2px 2px 3px 0px var(--wshadow1), inset -2px -2px 3px 0px var(--wshadow0);
  }
  .icon {
    margin-right: 12px;
    font-size: 21px;
    color: var(--sentence-ic);
    transition: opacity 0.3s ease-in-out;
    &:active {
      opacity: 0.5;
    }
  }
  .text {
    font-family: 'zcool-kuaile';
    font-size: 16px;
    color: var(--primary-text);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }
}
</style>
