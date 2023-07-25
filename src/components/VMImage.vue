<template>
  <div class="vm-image">
    <div class="vm-image-inner">
      <div class="vm-image-box" v-if="loaded" @click.stop="previewImage">
        <img :src="imgSrc" alt="每日一图" ref="imgDom" />
      </div>
      <div class="vm-image-mask" v-if="!loaded">
        <MNLSLoading :active="loading" />
        <span class="tip">{{ loadTip }}</span>
      </div>
    </div>
    <Teleport to="body">
      <Transition name="vm-scalein">
        <div class="vm-image-preview" v-if="isPreview">
          <div class="vm-image-preview-mask" @click.stop="closePreview"></div>
          <div class="vm-image-preview-box">
            <div class="vm-image-preview-inner">
              <img :src="imgSrc" alt="每日一图:预览" />
            </div>
            <div class="vm-image-preview-tools">
              <VMButton
                width="32px"
                height="32px"
                icon-class="ir-download"
                color="var(--download-ic)"
                @on-click="downloadImage" />
              <VMButton
                width="32px"
                height="32px"
                icon-class="ir-close"
                color="var(--close-ic)"
                @on-click="closePreview" />
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>
  </div>
</template>

<script setup lang="ts">
import { onBeforeMount, ref } from 'vue';
import { loadImage, downloadImg } from '@/utils/commonUtil';
import MNLSLoading from './MNLSLoading.vue';
import VMButton from '@/components/VMButton.vue';

const imgUrl = 'https://w.wallhaven.cc/full/2y/wallhaven-2y329m.png';

const imgDom = ref<HTMLElement>();
const imgSrc = ref('');
const isPreview = ref(false);

const loadTip = ref('蒙那粒砂正在向你袭来···');
const loading = ref(true);

const loaded = ref(false);

onBeforeMount(() => {
  loadImage(imgUrl)
    .then(res => {
      imgSrc.value = res.src;
      loaded.value = true;
    })
    .catch(err => {
      loading.value = false;
      loadTip.value = '她，迷路了(｀ﾟДﾟ´)ゞ';
    });
});
const previewImage = () => {
  isPreview.value = true;
};

const closePreview = () => {
  isPreview.value = false;
};

const downloadImage = () => {
  downloadImg(imgSrc.value);
};
</script>

<style lang="less" scoped>
.vm-image {
  width: 360px;
  height: 223px;
  box-sizing: border-box;
  padding: 8px;
  border-radius: 12px;
  transition: background-color 0.2s ease-in-out;
  background-color: var(--bg);
  box-shadow: -6px -6px 10px -1px var(--wshadow70), 6px 6px 10px -1px var(--bshadow15);
  .vm-image-inner {
    width: 100%;
    height: 100%;
    border-radius: 12px;
    box-shadow: inset -3px -3px 6px 0px var(--wshadow70), inset 3px 3px 6px 0px var(--bshadow15);
    box-sizing: border-box;
    padding: 3px;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .vm-image-mask {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    .tip {
      margin-top: 8px;
      font-family: 'zcool-kuaile';
      font-size: 12px;
      color: var(--primary-text);
    }
  }
  .vm-image-box {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    border-radius: 12px;
    overflow: hidden;
    img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
  }
}
</style>

<style lang="less">
.vm-image-preview {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 9999;
  background-color: var(--bg);
  display: flex;
  align-items: center;
  justify-content: center;
  .vm-image-preview-mask {
    z-index: -1;
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
  }
  .vm-image-preview-box {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    .vm-image-preview-inner {
      height: 80vh;
      box-sizing: border-box;
      padding: 8px;
      border-radius: 12px;
      box-shadow: inset -3px -3px 6px 0px var(--wshadow70), inset 3px 3px 6px 0px var(--bshadow15);
      img {
        height: 100%;
        object-fit: cover;
        border-radius: 12px;
      }
    }
    .vm-image-preview-tools {
      margin-top: 12px;
      display: flex;
      align-items: center;
      box-sizing: border-box;
      padding: 8px 18px;
      border-radius: 24px;
      box-shadow: inset -3px -3px 6px 0px var(--wshadow70), inset 3px 3px 6px 0px var(--bshadow15);
      column-gap: 12px;
      .ir-close {
        font-size: 14px;
      }
    }
  }
}
</style>
