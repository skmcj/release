<template>
  <Pc v-if="!isMobile" />
  <H5 v-if="isMobile" />
</template>

<script setup lang="ts">
import { getCurrentApi } from '@/api/indexApi';
import { printInfo, printSKMCJ, setGrayMode, setSStoreItem } from '@/utils/commonUtil';
import Pc from '@/views/Pc.vue';
import H5 from '@/views/H5.vue';
const isMobile = /Mobile|Android|iPhone/i.test(navigator.userAgent);

getCurrentApi()
  .then(res => {
    const data = res.data.data;
    setSStoreItem('userId', data.userId);
    setSStoreItem('levelId', data.levelId);
    setSStoreItem('stType', data.sentenceType);
    setSStoreItem('imgType', data.imageType);
    setSStoreItem('gray', data.grayMode);
    setSStoreItem('grayDate', data.grayDate);
    setGrayMode(data.grayMode, data.grayDate);
  })
  .catch(err => {});

// 发布标签
setTimeout(() => {
  console.clear();
  printSKMCJ();
  printInfo();
}, 1500);

// 禁止调试
// (() => {
//   function block() {
//     if (window.outerHeight - window.innerHeight > 200 || window.outerWidth - window.innerWidth > 200) {
//       document.body.innerHTML = '检测到非法调试,请关闭后刷新重试!';
//     }
//     setInterval(() => {
//       (function () {
//         return false;
//       })
//         ['constructor']('debugger')
//         ['call']();
//     }, 50);
//   }
//   try {
//     block();
//   } catch (err) {}
// })();
</script>
<style></style>
