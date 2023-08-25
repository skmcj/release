<template>
  <el-config-provider :locale="locale">
    <RouterView />
  </el-config-provider>
</template>

<script setup lang="ts">
import { RouterView } from 'vue-router';
import { useCurrentInfoStore } from '@/stores/currentinfo';
import { getCurrentApi } from '@/api/currentApi';
import { ElConfigProvider } from 'element-plus';
import zhCn from 'element-plus/dist/locale/zh-cn.mjs';

const locale = zhCn;

const initCurrent = () => {
  const { setCurrent } = useCurrentInfoStore();
  getCurrentApi().then(res => {
    if (res.code === 214) {
      setCurrent(res.data);
    }
  });
};

initCurrent();
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
// page
.page-view {
  width: 100%;
  display: inline-flex;
  flex-direction: column;
  box-sizing: border-box;
  padding: 18px 24px;
}
.page-inner {
  border-radius: 12px;
  background-color: $rlbg-page;
  box-sizing: border-box;
  padding: 18px 16px;
}
.page-top {
  display: flex;
  justify-content: space-between;
  box-sizing: border-box;
  padding: 8px 0;
  .page-tl {
    display: flex;
    align-items: center;
  }
  .page-tr {
    display: flex;
    align-items: center;
    justify-content: flex-end;
  }
  .cl-item {
    display: flex;
    align-items: center;
    .label {
      color: $rlt-title;
    }
  }
}
.page-mid {
  margin: 24px 0;
  .table-avatar {
    width: 4rem;
    height: 4rem;
    border-radius: 50%;
  }
  .sex-icon {
    font-size: 2rem;
    line-height: 2rem;
    &.ir-boy {
      color: $rlt-boy;
    }
    &.ir-girl {
      color: $rlt-girl;
    }
    &.ir-sex {
      color: $rlt-sex;
    }
  }
}
.page-bottom {
  margin: auto;
}
.page-form-bt {
  margin-top: 36px;
  width: 100%;
  box-sizing: border-box;
  padding: 18px 0;
  border-top: 1px solid $rlbd-default;
  display: flex;
  align-items: center;
  justify-content: center;
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
