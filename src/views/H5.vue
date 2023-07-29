<template>
  <div class="main">
    <!-- 顶部信息 -->
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
    <!-- tool -->
    <div class="tool-box">
      <VMClock size="36vw" />
      <VMWeather width="54vw" height="36vw" />
    </div>
    <div class="tool-box">
      <VMFate width="49.6vw" height="67.2vw" direction="vertical" />
      <div class="mess-list">
        <VMDigitalClock class="mess-item" width="40vw" height="14.4vw" />
        <VMBlock class="mess-item" width="40vw" height="9vw" font-size="14px" :message="datetime" />
        <div class="mess-bottom">
          <VMBlock
            width="12.8vw"
            height="37vw"
            direction="vertical"
            message="每日一图"
            font-size="24px"
            font-family="YouSheBiaoTiHei"
            is-btn />
          <div class="mess-br">
            <VMBlock width="24vw" height="9.6vw" message="留言" font-size="18px" font-family="YouSheBiaoTiHei" is-btn />
            <VMTaijiSwitch v-model="isDark" size="24vw" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onBeforeMount, watch } from 'vue';
import { openLink, formatDate, changeThemeMode } from '@/utils/commonUtil';
import Avatar from '@/components/Avatar.vue';
import VMBlock from '@/components/VMBlock.vue';
import VMButton from '@/components/VMButton.vue';
import VMClock from '@/components/VMClock.vue';
import VMWeather from '@/components/VMWeather.vue';
import VMFate from '@/components/VMFate.vue';
import VMDigitalClock from '@/components/VMDigitalClock.vue';
import VMTaijiSwitch from '@/components/VMTaijiSwitch.vue';

// 模式
const isDark = ref(false);

watch(isDark, val => {
  changeThemeMode(val ? 1 : 0);
});

// 当前日期
const datetime = ref('70/01/01 星期四');

onBeforeMount(() => {
  datetime.value = formatDate(new Date(), 'yy/MM/dd W');
});
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
.tool-box {
  display: flex;
  width: 100%;
  box-sizing: border-box;
  padding: 12px;
  justify-content: space-between;
}
.mess-list {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  .mess-bottom {
    width: 100%;
    display: flex;
    justify-content: space-between;
  }
  .mess-br {
    display: flex;
    flex-direction: column;
    height: 100%;
    justify-content: space-between;
  }
}
</style>
