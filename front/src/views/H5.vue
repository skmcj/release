<template>
  <div class="main">
    <!-- 顶部信息 -->
    <div class="top">
      <Avatar size="36vw" :url="avatar" />
      <div class="top-right">
        <VMBlock class="top-mess-item" :message="nickname" height="32px" padding="0 24px" radius="16px" in />
        <VMBlock class="top-mess-item" :message="address" height="32px" padding="0 24px" radius="16px" in />
        <div class="top-tools">
          <VMButton
            v-for="item of socialList"
            :key="item.id"
            class="icon-color"
            :icon-class="`ir-${item.icon}`"
            width="32px"
            height="32px"
            radius="16px"
            @on-click="() => openLink(item.link)"></VMButton>
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
          <VMImage
            type="button"
            :url="image"
            width="12.8vw"
            height="37vw"
            direction="vertical"
            message="每日一图"
            font-size="24px"
            font-family="YouSheBiaoTiHei" />

          <div class="mess-br">
            <VMBlock
              width="24vw"
              height="9.6vw"
              message="留言"
              font-size="18px"
              font-family="YouSheBiaoTiHei"
              is-btn
              @click.stop="openLWord" />
            <VMTaijiSwitch v-model="isDark" size="24vw" />
          </div>
        </div>
      </div>
    </div>
    <VMH5WorkList class="work-list" width="92vw" :work-list="productList" />
    <!-- 尾部 -->
    <div class="footer">
      <VMFooter :day="days" :year="year" :author="author" size="small" />
    </div>
    <VMH5LWord v-model="commentVisible" />
  </div>
</template>

<script setup lang="ts">
import { ref, onBeforeMount, watch } from 'vue';
import { openLink, formatDate, getSStoreItem } from '@/utils/commonUtil';
import Avatar from '@/components/Avatar.vue';
import VMBlock from '@/components/VMBlock.vue';
import VMImage from '@/components/VMImage.vue';
import VMButton from '@/components/VMButton.vue';
import VMClock from '@/components/VMClock.vue';
import VMWeather from '@/components/VMWeather.vue';
import VMFate from '@/components/VMFate.vue';
import VMDigitalClock from '@/components/VMDigitalClock.vue';
import VMTaijiSwitch from '@/components/VMTaijiSwitch.vue';
import VMH5WorkList from '@/components/VMH5WorkList.vue';
import VMH5LWord from '@/components/VMH5LWord.vue';
import VMFooter from '@/components/VMFooter.vue';
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

// 模式
const isDark = ref(false);
const { theme, setTheme } = useTheme();

// 留言
const commentVisible = ref(false);
const openLWord = () => {
  commentVisible.value = true;
};

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

watch(isDark, val => {
  setTheme(val ? 'dark' : 'light');
});

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
  getImageApi('phone')
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
.work-list {
  margin: auto;
}
.footer {
  margin: 0 auto;
  display: flex;
  justify-content: center;
  box-sizing: border-box;
  padding-top: 36px;
  padding-bottom: 18px;
}
</style>
