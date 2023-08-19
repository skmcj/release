<template>
  <Pc v-if="!isMobile" />
  <H5 v-if="isMobile" />
</template>

<script setup lang="ts">
import { getCurrentApi } from '@/api/indexApi';
import { setSStoreItem } from '@/utils/commonUtil';
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
  })
  .catch(err => {});
</script>

<style></style>
