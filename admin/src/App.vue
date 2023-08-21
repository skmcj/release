<template>
  <RouterView />
</template>

<script setup lang="ts">
import { RouterView } from 'vue-router';
</script>

<style lang="scss">
.avatar-role-info {
  display: flex;
  flex-direction: column;
  .info-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .tit {
    font-size: 1rem;
    font-weight: bold;
    color: $rlt-primary;
  }
  .text {
    font-size: 0.8rem;
    color: $rlt-second;
  }
}
</style>
