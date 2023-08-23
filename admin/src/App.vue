<template>
  <RouterView />
</template>

<script setup lang="ts">
import { RouterView } from 'vue-router';
</script>

<style lang="scss">
// 角色信息
.avatar-role-info {
  display: flex;
  flex-direction: column;
  .info-item {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    &.btn {
      margin-top: 8px;
      justify-content: center;
    }
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
// el-input拟态风
.el-input {
  &.ntair {
    .el-input__wrapper {
      transition: box-shadow 0.3s ease-in-out;
      background: none;
      box-shadow: -0.5px -0.5px 0px 0px $wshadow0, 0px 2px 3px -10px $bshadow5, 0.5px 0.5px 0px 0px $bshadow15,
        inset -4px -4px 6px -1px $wshadow70, inset 4px 4px 6px -1px $bshadow20;
      &.is-focus {
        box-shadow: -0.5px -0.5px 0px 0px $wshadow0, 0px 1px 3px -5px $bshadow5, 0.5px 0.5px 0px 0px $bshadow15,
          inset -2px -2px 3px 0 $wshadow70, inset 2px 2px 3px 0$bshadow20;
      }
    }
  }
}
</style>
