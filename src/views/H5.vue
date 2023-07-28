<template>
  <div class="main">
    <div class="top">
      <Avatar size="36vw" />
      <div class="top-right">
        <VMBlock class="top-mess-item" message="SKMCJ" height="32px" padding="0 24px" radius="16px" in />
        <VMBlock class="top-mess-item" message="Make In China" height="32px" padding="0 24px" radius="16px" in />
        <div class="top-tools">
          <VMButton
            icon-class="ir-github"
            color="var(--github-ic)"
            width="32px"
            height="32px"
            radius="16px"
            @on-click="() => openLink('https://github.com/skmcj')"></VMButton>
          <VMButton
            icon-class="ir-bilibili-tv"
            color="var(--bilibili-ic)"
            width="32px"
            height="32px"
            radius="16px"
            @on-click="() => openLink('https://www.bilibili.com/bangumi/media/md28228814')"></VMButton>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { openLink } from '@/utils/commonUtil';
import Avatar from '@/components/Avatar.vue';
import VMBlock from '@/components/VMBlock.vue';
import VMButton from '@/components/VMButton.vue';
</script>

<style lang="less" scoped>
.top {
  position: relative;
  display: flex;
  box-sizing: border-box;
  padding: 30px 24px 16px 24px;
  width: 100vw;
  height: 48vw;
  border-radius: 0 0 24px 24px;
  box-shadow: 0px 3px 8px 0 var(--bshadow15);
  background-color: var(--bg);
  .top-right {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    box-sizing: border-box;
    padding-top: 5px;
    padding-left: 12px;
  }
  .top-mess-item {
    & + .top-mess-item {
      margin-top: 12px;
    }
  }
  .top-tools {
    margin-top: 18px;
    display: flex;
    align-items: center;
    gap: 10px 12px;
    flex-wrap: wrap;
  }
}
</style>
