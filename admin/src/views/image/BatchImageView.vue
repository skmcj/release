<template>
  <div class="batch-image-view page-view">
    <div class="page-inner">
      <div class="page-rtop">
        <el-button link type="success" @click="formatJson">格式化</el-button>
        <el-tooltip effect="dark" content="格式化下方 Json 输入，快捷键：Ctrl + Alt" placement="top">
          <i class="ir-q-circle tip-icon"></i>
        </el-tooltip>
      </div>
      <el-input
        type="textarea"
        ref="jsonDom"
        v-model="batchJson"
        :rows="21"
        :placeholder="batchPlace"
        :class="{ 'is-error': batchTip !== '' }"
        @blur="validateJson"
        @keydown="caDown" />
      <span class="batch-tip" v-show="batchTip">{{ batchTip }}</span>
      <div class="page-form-bt">
        <el-button type="primary" @click="batch">确认添加</el-button>
        <el-button @click="back">取消</el-button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { ElButton, ElTooltip, ElInput } from 'element-plus';
import { isJson, showMessage } from '@/utils/commonUtil';
import { batchImageApi } from '@/api/imageApi';

const router = useRouter();
const back = () => {
  router.back();
};

// 批量新增
const jsonDom = ref();
const batchPlace =
  '输入规则(需满足下列条件)：\n- 数据包含在数组中，满足 json 格式\n- 每一项为一对象，结构如下，左侧为字段名；右侧为值类型，括号内为解释；* 号代表必填项，其它为可选项\n- 多项用逗号隔开\n例：\n[\n  {\n    "name": "string(图片名称)",\n    "url": "string(*图片链接)",\n    "type": "pc|phone|other(*图片显示类型)",\n    "imageType": "jpg|png|gif|jpeg|apng|avif|svg|webp|jif|ico|tiff(图片类型)",\n    "labels": "string[](图片标签)",\n    "w": "number(图片宽度)",\n    "h": "number(图片高度)",\n    "size": "number(图片存储大小)",\n    "disabled": "boolean(是否禁用)",\n    "showDate": "null|string(显示日期)"\n  }\n]\ntips：可以 Ctrl + Alt 组合键快速格式化';
const batchVisible = ref(false);
const batchJson = ref('');
const batchTip = ref('');
const validateJson = () => {
  if (batchJson.value === '') {
    batchTip.value = '数据为空';
    return false;
  }
  if (!/\[[\s\S]*?(\{.*?\})*?[\s\S]*\]/.test(batchJson.value)) {
    batchTip.value = 'json格式需为一个对象数组';
    return false;
  }
  if (!isJson(batchJson.value)) {
    batchTip.value = 'json 格式不正确';
    return false;
  } else {
    batchTip.value = '';
    return true;
  }
};
const formatJson = () => {
  if (isJson(batchJson.value)) {
    batchTip.value = '';
    const json = JSON.parse(batchJson.value);
    batchJson.value = JSON.stringify(json, null, 2);
  } else {
    batchTip.value = 'json 格式不正确';
  }
};
const batch = () => {
  if (validateJson()) {
    const json = JSON.parse(batchJson.value);
    batchImageApi(json).then(res => {
      if (res.code === 211) {
        showMessage('批量添加成功', 'success');
        back();
      } else {
        showMessage(res.msg, 'warning');
      }
    });
  }
};
// 监听 ctrl + Alt 组合键
const caDown = (e: any) => {
  if (e.ctrlKey && e.altKey) {
    // 格式化
    formatJson();
  }
};
</script>

<style lang="scss" scoped>
.el-textarea {
  margin-bottom: 1.5rem;
  &.is-error {
    margin-bottom: 0;
  }
}
</style>
