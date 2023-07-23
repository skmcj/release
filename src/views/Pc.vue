<template>
  <div class="main">
    <div class="top">
      <HeaderSvg class="bg" />
      <div class="top-content">
        <div class="right">
          <!-- 顶部信息：昵称、籍贯、社交账号、主题按钮 -->
          <div class="top-mess">
            <div class="top-mess_content">
              <VMBlock class="top-mess-item" padding="0 33px" message="SKMCJ" radius="21px" in />
              <VMBlock class="top-mess-item" padding="0 33px" message="Make In China" radius="21px" in />
              <VMButton
                class="top-mess-item"
                icon-class="ir-github"
                color="var(--github-ic)"
                @on-click="() => openLink('https://github.com/skmcj')"></VMButton>
              <VMButton
                class="top-mess-item"
                icon-class="ir-bilibili-tv"
                color="var(--bilibili-ic)"
                @on-click="() => openLink('https://www.bilibili.com/bangumi/media/md28228814')"></VMButton>
            </div>
            <div class="top-mess_mode show-title" data-title="切换主题">
              <VMSwitch
                v-model="isDark"
                show-icon
                icon="ir-sun"
                active-icon="ir-dark"
                color="var(--mode-c)"
                active-color="var(--mode-ac)" />
            </div>
          </div>
          <div class="top-tools" ref="toolsBox">
            <VMClock :size="toolHeight" />
            <div class="tools-date-box">
              <VMBlock message="23/07/26 星期日" />
              <VMDigitalClock />
            </div>
            <VMWeather :height="toolHeight" />
            <div></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';
import { changeThemeMode, openLink } from '@/utils/commonUtil';
import HeaderSvg from '@/components/HeaderSvg.vue';
import VMBlock from '@/components/VMBlock.vue';
import VMButton from '@/components/VMButton.vue';
import VMSwitch from '@/components/VMSwitch.vue';
import VMClock from '@/components/VMClock.vue';
import VMDigitalClock from '@/components/VMDigitalClock.vue';
import VMWeather from '@/components/VMWeather.vue';

const toolsBox = ref<HTMLElement>();
const toolHeight = ref(120);

const isDark = ref(false);

onMounted(() => {
  const toolsEl = toolsBox.value as HTMLElement;
  toolHeight.value = toolsEl.clientHeight - 12;
  console.log(toolHeight);
});

watch(isDark, val => {
  changeThemeMode(val ? 1 : 0);
});
</script>

<style lang="less" scoped>
.top {
  position: relative;
  width: 100%;
}
.bg {
  filter: drop-shadow(0px 3px 8px var(--bshadow15));
}
.top-content {
  position: absolute;
  top: 0;
  left: 50%;
  transform: translateX(-50%);
  width: 62.5%;
  height: 98%;
  box-sizing: border-box;
  display: flex;
  justify-content: flex-end;
  .right {
    position: relative;
    width: 78%;
    height: 100%;
    display: flex;
    flex-direction: column;
  }
}
.right {
  .top-mess {
    width: 100%;
    height: 42%;
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    .top-mess_content {
      display: flex;
      padding-bottom: 18px;
    }
    .top-mess-item {
      flex-shrink: 0;
      & + .top-mess-item {
        margin-left: 24px;
      }
    }
    .top-mess_mode {
      padding-bottom: 18px;
    }
  }
  .top-tools {
    box-sizing: border-box;
    padding-top: 12px;
    width: 100%;
    height: 58%;
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
  }
  .tools-date-box {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
    box-sizing: border-box;
    padding: 12px 0;
  }
}
@media (max-width: 1080px) {
  .top-content {
    width: 62.5%;
  }
}
@media (min-width: 1081px) and (max-width: 1680px) {
  .top-content {
    width: 1000px;
  }
}
@media (min-width: 1681px) and (max-width: 1920px) {
  .top-content {
    width: 1200px;
  }
}
@media (min-width: 1921px) {
  .top-content {
    width: 62.5%;
  }
}
</style>
