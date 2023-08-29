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
  min-width: 100%;
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
    column-gap: 8px;
  }
  .cl-item {
    display: flex;
    align-items: center;
    .label {
      color: $rlt-title;
    }
  }
  .el-button + .el-button {
    margin: 0;
  }
}
.page-ltop {
  display: flex;
  justify-content: flex-start;
  align-items: center;
  box-sizing: border-box;
  padding: 8px 0;
}
.page-rtop {
  display: flex;
  justify-content: flex-end;
  align-items: center;
  box-sizing: border-box;
  padding: 8px 0;
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
.page-lrbox {
  display: flex;
  justify-content: space-between;
}
.inline-center {
  .cell {
    text-align: center;
  }
}
.dialog-footer {
  button + button {
    margin-left: 10px;
  }
}
.preview-img {
  &.el-table__cell .cell {
    text-overflow: unset;
  }
}
.labels {
  .cell {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 5px 6px;
  }
}
.levels {
  .cell {
    display: inline-flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 5px 6px;
  }
  .el-tag--light {
    @for $i from 1 to 12 {
      &:nth-child(#{$i}) {
        background-color: mix($rlc-white, map-get($rlc-map, level#{$i}), 90%);
        border-color: mix($rlc-white, map-get($rlc-map, level#{$i}), 80%);
        color: map-get($rlc-map, level#{$i});
      }
    }
  }
  .el-tag--dark {
    @for $i from 1 to 12 {
      &:nth-child(#{$i}) {
        background-color: map-get($rlc-map, level#{$i});
        border-color: map-get($rlc-map, level#{$i});
        color: $rlc-white;
      }
    }
  }
  .el-tag--plain {
    @for $i from 1 to 12 {
      &:nth-child(#{$i}) {
        background-color: transparent;
        border-color: mix($rlc-white, map-get($rlc-map, level#{$i}), 50%);
        color: map-get($rlc-map, level#{$i});
      }
    }
  }
}
.tip-icon {
  margin: 0 6px;
  font-size: 0.7rem;
  transition: color 0.3s ease;
  &:hover {
    color: $rlc-warning;
  }
}

// 批量添加框提示
.batch-tip {
  line-height: 1.5rem;
  color: $rlt-error;
  margin-left: 5px;
}
.el-textarea {
  &.is-error {
    .el-textarea__inner,
    .el-textarea__inner:focus {
      box-shadow: 0 0 0 1px $rlt-error inset;
    }
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

.el-dialog {
  &.lradius {
    border-radius: 12px;
  }
}

.el-select-dropdown {
  &.icon-select-list {
    .el-select-dropdown__list {
      margin: auto;
      width: 188px;
      display: flex;
      flex-wrap: wrap;
      padding: 8px;
      box-sizing: border-box;
    }
    .el-select-dropdown__item {
      position: relative;
      border-radius: 5px;
      padding: 0;
      width: 34px;
      text-align: center;
      .ir-bilibili {
        font-size: 12px;
      }
      &.selected {
        &::after {
          position: absolute;
          bottom: 0;
          left: 50%;
          transform: translateX(-50%);
          content: '';
          width: 64%;
          height: 3px;
          border-radius: 1.5px;
          background-color: $rlc-primary;
        }
      }
    }
  }
}

.tag-box {
  .el-form-item__content {
    gap: 5px 6px;
  }
}

textarea {
  &::-webkit-scrollbar {
    width: 6px;
    height: 6px;
  }
  &::-webkit-scrollbar-thumb {
    background-color: #8d8d8d;
    border-radius: 3px;
  }
}
</style>
