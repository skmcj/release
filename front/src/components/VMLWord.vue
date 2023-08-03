<template>
  <div class="vm-lword" :class="{ 'is-active': isActive }">
    <div class="vm-lword-box vm-lword-front">
      <div class="vm-lword-title">
        <span class="text">留言板</span>
        <i class="icon ir-taiji" @click.stop="rotate"></i>
      </div>
      <div class="vm-lword-inner">
        <div class="vm-lword-list">
          <VMLWordItem v-for="i in 10" />
        </div>
      </div>
    </div>
    <div class="vm-lword-box vm-lword-behind">
      <div class="vm-lword-title">
        <span class="text">留言</span>
        <i class="icon ir-taiji is-active" @click.stop="rotate"></i>
      </div>
      <div class="vm-lword-inner">
        <VMLWordForm class="form" />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import VMLWordItem from './VMLWordItem.vue';
import VMLWordForm from './VMLWordForm.vue';

const isActive = ref(false);

// 翻转版面
const rotate = () => {
  isActive.value = !isActive.value;
};
</script>

<style lang="less" scoped>
.vm-lword {
  position: relative;
  user-select: none;
  width: 360px;
  height: 290px;
  perspective: 1600px;
  &.is-active {
    .vm-lword-front {
      transform: rotateY(-180deg);
    }
  }
  &:not(.is-active) {
    .vm-lword-behind {
      transform: rotateY(180deg);
    }
  }
}
.vm-lword-box {
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  box-sizing: border-box;
  padding: 8px;
  border-radius: 12px;
  transition: background-color 0.2s ease-in-out;
  background-color: var(--bg);
  box-shadow: -6px -6px 10px -1px var(--wshadow70), 6px 6px 10px -1px var(--bshadow15);
}
.vm-lword-title {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  width: 100%;
  margin-bottom: 12px;
  .text {
    margin-left: 4px;
    font-size: 24px;
    font-family: 'YouSheBiaoTiHei';
    color: var(--primary-text);
  }
  .icon {
    margin-right: 12px;
    font-size: 18px;
    color: var(--light-gray);
    transition: transform 0.3s linear;
    &.is-active {
      transform: rotate(180deg);
    }
  }
}
.vm-lword-inner {
  width: 100%;
  height: 100%;
  border-radius: 12px;
  box-shadow: inset -3px -3px 6px 0px var(--wshadow70), inset 3px 3px 6px 0px var(--bshadow15);
  box-sizing: border-box;
  display: flex;
  &::-webkit-scrollbar {
    display: none;
  }
}

.vm-lword-front,
.vm-lword-behind {
  transform-style: preserve-3d;
  backface-visibility: hidden;
  position: absolute;
  top: 0;
  left: 0;
  transition: transform 1.2s ease-in-out;
}
.vm-lword-front {
  .vm-lword-inner {
    overflow-x: hidden;
    overflow-y: auto;
    padding: 16px 21px;
  }
}
.vm-lword-behind {
  .form {
    // z-index: -1;
  }
}
.vm-lword-list {
  z-index: -1;
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 100%;
  // height: 100%;
  box-sizing: border-box;
  &::after {
    content: '';
    display: block;
    width: 100%;
    height: 16px;
    flex-shrink: 0;
  }
}
</style>
