<template>
  <nav class="main-nav">
    <span class="title">{{ routeinfo.title }}</span>
    <el-popover placement="bottom" :width="120" trigger="hover">
      <template #reference>
        <div class="avatar">{{ firstA(roleinfo.username) }}</div>
      </template>
      <div class="avatar-role-info">
        <div class="info-item">
          <span class="tit">用户名：</span>
          <span class="text">{{ roleinfo.username }}</span>
        </div>
        <div class="info-item" v-if="roleinfo.roleText">
          <span class="tit">权限：</span>
          <span class="text">{{ roleinfo.roleText }}</span>
        </div>
      </div>
    </el-popover>
  </nav>
</template>

<script setup lang="ts">
import { ElPopover } from 'element-plus';
import { storeToRefs } from 'pinia';
import { useRoleInfoStore } from '@/stores/roleinfo';
import { useRouteInfoStore } from '@/stores/routeinfo';

const firstA = (str: string) => {
  return str[0];
};

const { roleinfo } = storeToRefs(useRoleInfoStore());
const { routeinfo } = storeToRefs(useRouteInfoStore());
</script>

<style lang="scss" scoped>
.main-nav {
  width: 100%;
  height: 4.5rem;
  display: flex;
  background-color: #fff;
  display: flex;
  align-items: center;
  justify-content: space-between;
  box-sizing: border-box;
  padding: 0 18px;
  padding-right: 36px;
  .title {
    font-size: 1rem;
    font-weight: bold;
    color: $rlt-primary;
  }
  .avatar {
    user-select: none;
    color: #8d8d8d;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 3rem;
    height: 3rem;
    border-radius: 50%;
    box-shadow: -0.5px -0.5px 0px 0px #ffffff, 0.5px 0.5px 0px 0px rgba(0, 0, 0, 0.15),
      inset -3px -3px 6px 0 rgba(255, 255, 255, 0.7), inset 3px 3px 6px 0 rgba(0, 0, 0, 0.2);
    text-transform: uppercase;
    font-size: 2rem;
    font-weight: bold;
    text-shadow: -1px -1px 3x rgba(255, 255, 255, 0.7), 1px 1px 3px rgba(0, 0, 0, 0.2);
  }
}
</style>
