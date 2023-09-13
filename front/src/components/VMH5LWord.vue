<template>
  <Transition name="vm-lfade" @enter="lockScroll" @before-leave="removeScroll">
    <div class="vm-h5-lword" v-if="modelValue">
      <div class="vm-h5-lword-mask" @click.stop="close"></div>
      <div class="vm-h5-lword-inner inner">
        <div class="vm-h5-lword-tabs">
          <div class="vm-h5-lword-tab" :class="{ 'is-active': mode === 0 }" @click.stop="changeMode(0)">留言板</div>
          <div class="vm-h5-lword-tab" :class="{ 'is-active': mode === 1 }" @click.stop="changeMode(1)">留言</div>
        </div>
        <div class="vm-h5-lword-main">
          <div class="vm-h5-lword-content" :class="{ 'is-active': mode === 1 }">
            <div class="vm-h5-lword-content-box list">
              <div class="vm-lword-list">
                <VMLWordItem
                  v-for="item of commentList"
                  :key="item.id"
                  :nickname="item.nickname"
                  :date="item.showDate"
                  :text="item.content" />
              </div>
              <div v-if="currentPage < totalPage" class="load-more" @click.stop="clickMore">加载更多</div>
            </div>
            <div class="vm-h5-lword-content-box">
              <VMLWordForm class="form" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup lang="ts">
import { ref, onBeforeMount } from 'vue';
import VMLWordForm from './VMLWordForm.vue';
import VMLWordItem from './VMLWordItem.vue';
import { getCommentApi, type Comment } from '@/api/indexApi';

// 打开|锁住 底层滚动
const lockScroll = () => {
  document.body.classList.add('lock-scroll');
};

const removeScroll = () => {
  document.body.classList.remove('lock-scroll');
};

const close = () => {
  emits('update:modelValue', false);
};

// 留言显示
const commentList = ref<Comment[]>([]);
const currentPage = ref(1);
const totalPage = ref(0);

onBeforeMount(() => {
  getComment(1);
});

const getComment = (page = 1) => {
  getCommentApi(page)
    .then(res => {
      if (!res.data) return;
      const data = res.data.data;
      if (!data) return;
      if (data.list.length > 0) {
        if (page === 1) {
          commentList.value = data.list;
        } else {
          commentList.value.push(...data.list);
        }
      }
      totalPage.value = data.totalPage;
    })
    .catch(err => {});
};

const clickMore = () => {
  currentPage.value += 1;
  getComment(currentPage.value);
};

interface VMH5LWordProps {
  modelValue?: boolean;
}

const props = withDefaults(defineProps<VMH5LWordProps>(), {
  modelValue: false
});

// 留言|留言板
const mode = ref(0);
const changeMode = (val: number = 0) => {
  mode.value = val;
  if (!mode.value) {
    currentPage.value = 1;
    getComment(1);
  }
};

const emits = defineEmits(['update:modelValue']);
</script>

<style lang="less" scoped>
.vm-h5-lword {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
}
.vm-h5-lword-mask {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: var(--mask-bg);
}
.vm-h5-lword-inner {
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 42vh;
  background-color: var(--bg);
  box-shadow: -3px -3px 6px 0px var(--wshadow70);
  border-radius: 21px 21px 0px 0px;
  display: flex;
  flex-direction: column;
  align-items: center;
  box-sizing: border-box;
  padding: 20px 15px 32px 15px;
  &::before {
    content: '';
    position: absolute;
    left: 50%;
    top: 6px;
    transform: translateX(-50%);
    width: 12px;
    height: 3px;
    border-radius: 1.5px;
    opacity: 1;
    box-shadow: inset -1px -1px 3px 0px var(--wshadow70), inset 1px 1px 3px 0px var(--bshadow15);
  }
}
.vm-h5-lword-tabs {
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 100%;
  .vm-h5-lword-tab {
    user-select: none;
    cursor: pointer;
    font-family: 'YouSheBiaoTiHei';
    font-size: 1.5rem;
    color: var(--primary-text);
    box-sizing: border-box;
    padding: 3px 6px;
    border-radius: 0.5rem;
    transition: box-shadow 0.1s linear, transform 0.1s linear, background-color 0.2s ease-in-out;
    box-shadow: -2px -2px 6px 0 var(--wshadow70), 3px 3px 6px 0 var(--bshadow15);
    &.is-active {
      box-shadow: -0.5px -0.5px 0px 0px var(--wshadow0), 0px 2px 3px -10px var(--bshadow5),
        0.5px 0.5px 0px 0px var(--bshadow15), inset -4px -4px 6px -1px var(--wshadow70),
        inset 4px 4px 6px -1px var(--bshadow20);
    }
  }
}
.vm-h5-lword-main {
  margin-top: 12px;
  width: 100%;
  height: 100%;
  border-radius: 12px;
  box-shadow: inset -3px -3px 6px 0px var(--wshadow70), inset 3px 3px 6px 0px var(--bshadow15);
  box-sizing: border-box;
  display: flex;
  align-items: center;
  overflow: hidden;
  .vm-h5-lword-content {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    transition: transform 0.5s ease-in-out;
    &.is-active {
      transform: translateX(-100%);
    }
  }
  .vm-h5-lword-content-box {
    width: 100%;
    height: 100%;
    flex-shrink: 0;

    &.list {
      box-sizing: border-box;
      overflow-x: hidden;
      overflow-y: auto;
      padding: 16px 21px;
      &::-webkit-scrollbar {
        display: none;
      }
      .load-more {
        text-align: center;
        cursor: pointer;
        color: var(--more-link-text);
        font-size: 12px;
        box-sizing: border-box;
        padding: 5px 0;
        font-family: 'zcool-kuaile';
      }
    }
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
