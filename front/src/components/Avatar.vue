<template>
  <div class="avatar-box" :style="aStyle">
    <div class="in-box">
      <img :src="url ?? avatarUrl" alt="avatar" />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import avatarUrl from '@/assets/images/default-avatar.png';
import { getPropsStyle } from '@/utils/commonUtil';

interface AvatarProps {
  size?: number | string;
  url?: string;
}
const props = withDefaults(defineProps<AvatarProps>(), {
  size: undefined,
  url: undefined
});

const aStyle = computed(() => {
  const style: any = {};
  if (props.size) {
    style.width = getPropsStyle(props.size);
    style.height = getPropsStyle(props.size);
  }
  return style;
});
</script>

<style lang="less" scoped>
.avatar-box {
  position: relative;
  width: 120px;
  height: 120px;
  border-radius: 50%;
  background-color: var(--bg);
  box-shadow: -6px -6px 10px -1px var(--wshadow70), 6px 6px 10px -1px var(--bshadow15);
  box-sizing: border-box;
  border: 1px solid var(--w-border);
  display: flex;
  align-items: center;
  justify-content: center;
  .in-box {
    width: 90%;
    height: 90%;
    border-radius: 50%;
    overflow: hidden;
    img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
  }
}
@media (max-width: 1080px) {
  .avatar-box {
    width: 72px;
    height: 72px;
  }
}
@media (min-width: 1081px) and (max-width: 1680px) {
  .avatar-box {
    width: 108px;
    height: 108px;
  }
}
@media (min-width: 1681px) and (max-width: 1920px) {
  .avatar-box {
    width: 120px;
    height: 120px;
  }
}
@media (min-width: 1921px) {
  .avatar-box {
    width: 156px;
    height: 156px;
  }
}
</style>
