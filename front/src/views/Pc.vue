<template>
  <div class="main">
    <!-- 顶部 -->
    <div class="top">
      <HeaderSvg class="bg" :avatar="avatar" />
      <div class="top-content">
        <div class="right">
          <!-- 顶部信息：昵称、籍贯、社交账号、主题按钮 -->
          <div class="top-mess">
            <div class="top-mess_content">
              <VMBlock class="top-mess-item" padding="0 33px" :message="nickname" radius="21px" in />
              <VMBlock class="top-mess-item" padding="0 33px" :message="address" radius="21px" in />
              <VMButton
                v-for="item of socialList"
                :key="item.id"
                class="top-mess-item"
                :icon-class="`ir-${item.icon}`"
                :color="`var(--${item.icon}-ic)`"
                @on-click="() => openLink(item.link)"></VMButton>
              <!-- <VMButton
                class="top-mess-item"
                icon-class="ir-bilibili-tv"
                color="var(--bilibili-tv-ic)"
                @on-click="() => openLink('https://www.bilibili.com/bangumi/media/md28228814')"></VMButton> -->
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
              <VMBlock :message="datetime" />
              <VMDigitalClock />
            </div>
            <VMWeather :height="toolHeight" />
            <VMFate :height="toolHeight" />
          </div>
        </div>
      </div>
    </div>
    <!-- 中部 -->
    <div class="mid" ref="midBox">
      <VMSentence :message="sentence" />
      <div class="mid-content" ref="midContent">
        <div class="mid-content-inner" ref="midInner">
          <div class="mid-left">
            <VMImage :url="image" />
            <VMLWord />
          </div>
          <VMSkillBar :value="level" :skill-list="levelList" />
          <VMWorkList :work-list="productList" />
        </div>
      </div>
    </div>
    <!-- 尾部 -->
    <div class="footer">
      <VMFooter :day="days" :year="year" :author="author" />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, watch, onUpdated, onBeforeMount } from 'vue';
import { openLink, formatDate, getSStoreItem } from '@/utils/commonUtil';
import HeaderSvg from '@/components/HeaderSvg.vue';
import VMBlock from '@/components/VMBlock.vue';
import VMButton from '@/components/VMButton.vue';
import VMSwitch from '@/components/VMSwitch.vue';
import VMClock from '@/components/VMClock.vue';
import VMDigitalClock from '@/components/VMDigitalClock.vue';
import VMWeather from '@/components/VMWeather.vue';
import VMFate from '@/components/VMFate.vue';
import VMSentence from '@/components/VMSentence.vue';
import VMFooter from '@/components/VMFooter.vue';
import VMImage from '@/components/VMImage.vue';
import VMLWord from '@/components/VMLWord.vue';
import VMSkillBar from '@/components/VMSkillBar.vue';
import VMWorkList from '@/components/VMWorkList.vue';
import {
  getUserInfoApi,
  getSTDailyApi,
  getSTRandomApi,
  getImgDailyApi,
  getImgRandomApi,
  getLevelListApi,
  getProductApi
} from '@/api/indexApi';
import type { Social } from '@/api/indexApi';
import useTheme from '@/hooks/useTheme';

// 顶部下方工具栏
const toolsBox = ref<HTMLElement>();
const toolHeight = ref(120);

// 中间盒子
const midBox = ref<HTMLElement>();
const midContent = ref<HTMLElement>();
const midInner = ref<HTMLElement>();

// mess
const nickname = ref('SKMCJ');
const address = ref('Make In China');
const avatar = ref('');
const days = ref(0);
const year = ref(1970);
const author = ref('SKMCJ');
const level = ref(0);
const levelList = ref<any[]>(['成仙', '渡劫', '大乘', '合体', '炼虚', '化神', '元婴', '结丹', '筑基', '练气']);
const socialList = ref<Social[]>([]);
const sentence = ref('');
const image = ref('');
const productList = ref<any>([]);

// 模式
const isDark = ref(false);
const { theme, setTheme } = useTheme();

// 当前日期
const datetime = ref('70/01/01 星期四');

onBeforeMount(() => {
  datetime.value = formatDate(new Date(), 'yy/MM/dd W');
  const userId = getSStoreItem('userId');
  const levelId = getSStoreItem('levelId');
  const imgType = getSStoreItem('imgType');
  const stType = getSStoreItem('stType');
  getUserInfo(userId);
  getImage(imgType);
  getSentence(stType);
  getLevelList(levelId);
  getProductList();
  getTheme();
});

const getTheme = () => {
  if (theme.value === 'dark') isDark.value = true;
};

const getUserInfo = (userId: string | undefined = undefined) => {
  if (!userId) return;
  getUserInfoApi(userId)
    .then(res => {
      const data = res.data.data;
      nickname.value = data.nickname;
      address.value = data.address;
      avatar.value = data.avatar;
      socialList.value = data.social;
      level.value = data.level;
      author.value = data.author;
      days.value = data.days;
      year.value = data.year;
    })
    .catch(err => {});
};

const getImage = (type = 0) => {
  let getImageApi = getImgDailyApi;
  if (type > 0) {
    getImageApi = getImgRandomApi;
  }
  getImageApi()
    .then(res => {
      const data = res.data.data;
      image.value = data.url;
    })
    .catch(err => {});
};

const getSentence = (type = 0) => {
  let getSentenceApi = getSTDailyApi;
  if (type > 0) {
    getSentenceApi = getSTRandomApi;
  }
  getSentenceApi()
    .then(res => {
      const data = res.data.data;
      sentence.value = data.content;
    })
    .catch(err => {});
};

const getLevelList = (id: string) => {
  getLevelListApi(id)
    .then(res => {
      const data = res.data.data;
      levelList.value = data.labels;
    })
    .catch(err => {});
};

const getProductList = (page = 1, pageSize = 5) => {
  getProductApi(page, pageSize)
    .then(res => {
      const data = res.data.data;
      if (data) {
        productList.value = data.list;
      }
    })
    .catch(err => {});
};

onMounted(() => {
  const toolsEl = toolsBox.value as HTMLElement;
  toolsEl && (toolHeight.value = toolsEl.clientHeight - 12);
  caleMidContent();
});

onUpdated(() => {
  caleMidContent();
});

watch(isDark, val => {
  setTheme(val ? 'dark' : 'light');
});

function caleMidContent() {
  if (!midContent.value || !midInner.value) return;
  const contentEl = midContent.value;
  const innerEl = midInner.value;
  const midEl = midBox.value as HTMLElement;
  const midWidth = midEl.getBoundingClientRect().width;
  const scale = midWidth / 1200;
  if (scale !== 1) {
    innerEl.style.transform = `scale(${scale})`;
    innerEl.style.transformOrigin = 'left top';
    const newRect = innerEl.getBoundingClientRect();
    contentEl.style.width = `${midWidth}px`;
    contentEl.style.height = `${newRect.height}px`;
  }
}
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

.mid {
  margin: 0 auto;
  margin-top: 18px;
  display: flex;
  flex-direction: column;
  .mid-content {
    margin-top: 24px;
    .mid-content-inner {
      width: 1200px;
      height: 540px;
      display: flex;
      justify-content: space-between;
    }
    .mid-left,
    .mid-mid,
    .mid-right {
      display: flex;
      height: 100%;
    }
    .mid-left {
      flex-direction: column;
      align-items: center;
      justify-content: space-between;
    }
  }
}

.footer {
  margin: 0 auto;
  display: flex;
  justify-content: center;
  box-sizing: border-box;
  padding-top: 36px;
  padding-bottom: 48px;
}
@media (max-width: 1080px) {
  .top-content,
  .mid,
  .footer {
    width: 62.5%;
  }
}
@media (min-width: 1081px) and (max-width: 1680px) {
  .top-content,
  .mid,
  .footer {
    width: 1000px;
  }
}
@media (min-width: 1681px) and (max-width: 1920px) {
  .top-content,
  .mid,
  .footer {
    width: 1200px;
  }
}
@media (min-width: 1921px) {
  .top-content,
  .mid,
  .footer {
    width: 62.5%;
  }
}
</style>
