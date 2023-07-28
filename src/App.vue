<template>
  <Pc v-if="!isMobile" />
  <H5 v-if="isMobile" />
</template>

<script setup lang="ts">
import { onBeforeMount } from 'vue';
import Pc from '@/views/Pc.vue';
import H5 from '@/views/H5.vue';

const isMobile = /Mobile|Android|iPhone/i.test(navigator.userAgent);
</script>

<style></style>
