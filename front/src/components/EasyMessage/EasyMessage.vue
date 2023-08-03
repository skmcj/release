<template>
  <!-- 封装一个简单的全局消息组件 -->
  <Transition name="message-fade" @before-leave="onClose" @after-leave="destroy">
    <div
      v-show="visible"
      ref="messageRef"
      :id="`${id}`"
      class="message"
      :class="{
        [`message-${type}`]: type && !icon,
        'is-center': center,
        closeable: showClose
      }"
      :style="cStyle"
      role="alert"
      @mouseenter="clearTimer"
      @mouseleave="startTimer">
      <div class="message-inner">
        <slot>
          <p class="message-content">
            {{ message }}
          </p>
        </slot>
        <i v-if="showClose" class="message-close ic-close" @click.stop="close"> </i>
      </div>
    </div>
  </Transition>
</template>

<script setup lang="ts">
import type { VNode, CSSProperties } from 'vue';
import { ref, computed, onMounted } from 'vue';

defineOptions({
  name: 'EsMessage'
});

export type MessageType = 'success' | 'error' | 'info' | 'warning' | 'tip';
export type IconType = string | object | Function;

export interface EsMessageProps {
  id?: number;
  type?: MessageType;
  duration?: number;
  zIndex?: number;
  message?: string | VNode;
  offset?: number;
  icon?: IconType;
  showClose?: boolean;
  center?: boolean;
  onDestroy?: () => void;
  onClose?: () => void;
}

const props = withDefaults(defineProps<EsMessageProps>(), {
  type: 'tip',
  duration: 2000,
  message: '',
  offset: 20,
  icon: undefined,
  showClose: false,
  center: false,
  onDestroy: () => {},
  onClose: () => {}
});

// 组件能见度
const visible = ref(false);

const cStyle = computed<CSSProperties>(() => ({
  top: `${props.offset}px`,
  zIndex: props.zIndex
}));

let dTimer: number | undefined;

function startTimer() {
  if (props.duration === 0) return;
  setTimeout(() => {
    close();
  }, props.duration);
}

function clearTimer() {
  dTimer && clearTimeout(dTimer);
}

function close() {
  visible.value = false;
}

function destroy() {
  props.onDestroy();
}

onMounted(() => {
  startTimer();
  visible.value = true;
});
</script>

<style lang="less">
.message {
  width: -webkit-fit-content;
  width: -moz-fit-content;
  width: fit-content;
  max-width: calc(100% - 32px);
  box-sizing: border-box;
  border-radius: 5px;
  position: fixed;
  left: 50%;
  top: 20px;
  transform: translateX(-50%);
  background-color: var(--select-bg);
  box-shadow: 2px 2px 8px var(--bshadow15);
  transition: opacity 0.3s, transform 0.4s, top 0.4s;
  padding: 6px;
  display: flex;
  align-items: center;
  &.is-center {
    justify-content: center;
  }
  &.closeable {
    padding-right: 42px;
  }
  // &.message-tip {}
  // &.message-info {}
  // &.message-warning {}
  // &.message-success {}

  .message-inner {
    display: flex;
    align-items: center;
    box-sizing: border-box;
    padding: 5px 8px;
    color: var(--primary-text);
    box-shadow: inset -3px -3px 6px 0px var(--wshadow70), inset 3px 3px 6px 0px var(--bshadow15);
    border-radius: 5px;
  }
  .message-content {
    margin: 0;
    white-space: pre-wrap;
    text-align: center;
  }
  .message-close {
    color: #ccc;
    font-size: 12px;
    position: absolute;
    top: 50%;
    right: 14px;
    transform: translateY(-50%);
    cursor: pointer;
    transition: color 0.5s linear;
    &:focus {
      outline-width: 0;
    }
    &:hover {
      color: #d23918;
    }
  }
}

.message-fade-enter-active,
.message-fade-leave-active {
  transition: opacity 0.3s linear;
}

.message-fade-enter-from,
.message-fade-leave-to {
  opacity: 0;
}
</style>
