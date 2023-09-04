<template>
  <div class="config-view page-view">
    <div class="page-inner">
      <div class="page-block-title">前台显示</div>
      <div class="page-block">
        <div class="page-block-item">
          <label class="label">展示用户：</label>
          <el-select v-model="form.userId" placeholder="选择展示用户">
            <el-option v-for="item in userList" :key="item.id" :label="item.nickname" :value="item.id" />
          </el-select>
        </div>
        <div class="page-block-item">
          <label class="label">展示境界：</label>
          <el-select v-model="form.levelId" placeholder="选择境界组">
            <el-option v-for="item in levelList" :key="item.id" :label="item.tip" :value="item.id" />
          </el-select>
        </div>
        <div class="page-block-item">
          <label class="label">句子展示：</label>
          <el-select v-model="form.sentenceType" placeholder="选择句子类型">
            <el-option v-for="item in stTypeList" :key="item.id" :label="item.label" :value="item.value" />
          </el-select>
        </div>
        <div class="page-block-item">
          <label class="label">图片展示：</label>
          <el-select v-model="form.imageType" placeholder="选择图片类型">
            <el-option v-for="item in imgTypeList" :key="item.id" :label="item.label" :value="item.value" />
          </el-select>
        </div>
      </div>
      <div class="page-block-title">模式显示</div>
      <div class="page-block">
        <div class="page-block-item">
          <label class="label">灰度模式：</label>
          <el-select v-model="form.grayMode" placeholder="选择灰度模式">
            <el-option v-for="item in grayList" :key="item.id" :label="item.label" :value="item.value" />
          </el-select>
        </div>
        <div class="page-block-item">
          <label class="label">指定日期：</label>
          <div class="content tags">
            <!-- 标签列表 -->
            <el-tag
              v-for="tag in form.grayDate"
              :key="tag"
              closable
              :disable-transitions="false"
              @close="handleTagClose(tag)"
              >{{ tag }}</el-tag
            >
            <!-- 标签输入框 -->
            <el-date-picker
              v-if="tagInputVisible"
              ref="tagInputDom"
              size="small"
              style="width: 96px"
              v-model="inputValue"
              type="date"
              popper-class="picker-date"
              placeholder="选择日期"
              format="MM-DD"
              value-format="MM-DD"
              @change="handleTagInput" />
            <!-- 标签添加按钮 -->
            <el-button v-else style="width: 96px" size="small" @click="showTagInput"> + 添加日期 </el-button>
          </div>
        </div>
      </div>
      <div class="page-form-bt">
        <el-button type="primary" @click="confirm">保存</el-button>
        <el-button @click="back">取消</el-button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { nextTick, onBeforeMount, reactive, ref } from 'vue';
import { ElInput, ElSelect, ElOption, ElButton, ElDatePicker, ElTag } from 'element-plus';
import type { CurrentInfo, LevelShortInfo, UserShortInfo } from '@/types';
import { showMessage } from '@/utils/commonUtil';
import { useRouter } from 'vue-router';
import { useCurrentInfoStore } from '@/stores/currentinfo';
import { getUserListApi } from '@/api/userApi';
import { getLevelListApi } from '@/api/levelApi';
import { getCurrentApi, setCurrentApi } from '@/api/currentApi';

const router = useRouter();
const back = () => {
  router.back();
};

// 前台展示
const userList = ref<UserShortInfo[]>([]);
const levelList = ref<LevelShortInfo[]>([]);
const stTypeList = [
  {
    id: 'a7350f74ca1af81f106d6012ced18f05',
    value: 0,
    label: '日常'
  },
  {
    id: 'adfabd78d407607ea0a2fa6d545fd1b9',
    value: 1,
    label: '随机'
  }
];
const imgTypeList = [
  {
    id: 'e584dfec9756800de07aa88300038776',
    value: 0,
    label: '日常'
  },
  {
    id: '1567a46e79f69f159edcbeba19683b9f',
    value: 1,
    label: '随机'
  }
];

const form = reactive<CurrentInfo>({});

let originData: CurrentInfo = {};

const getCurrentInfo = () => {
  const { setCurrent } = useCurrentInfoStore();
  getCurrentApi().then(res => {
    if (res.code === 214) {
      setCurrent(res.data);
      originData = JSON.parse(JSON.stringify(res.data));
      Object.assign(form, res.data);
    }
  });
};

const getUserList = () => {
  getUserListApi().then(res => {
    if (res.data) {
      userList.value = res.data;
    }
  });
};

const getLevelList = () => {
  getLevelListApi().then(res => {
    if (res.data) {
      levelList.value = res.data;
    }
  });
};

onBeforeMount(() => {
  getCurrentInfo();
  getUserList();
  getLevelList();
});

// 保存
const confirm = () => {
  let key: keyof CurrentInfo;
  const data: any = {};
  for (key in form) {
    if (JSON.stringify(form[key]) !== JSON.stringify(originData[key])) {
      data[key] = form[key];
    }
  }
  if (JSON.stringify(data) === '{}') {
    showMessage('暂无修改，无需保存');
    return;
  }
  form['id'] && (data['id'] = form['id']);
  setCurrentApi(data).then(res => {
    if (res.code === 213) {
      showMessage('设置成功', 'success');
      getCurrentInfo();
    } else {
      showMessage(res.msg, 'warning');
    }
  });
};

const grayList = [
  { id: 'fc1d21a03ab4429bcea49e833b9ae283', value: 0, label: '正常' },
  { id: '6b40c9637a02c5cb1cd919769c93cb0a', value: 1, label: '灰度' },
  { id: '30ec0f6d781642f4bdb4d3e8c2746f7d', value: 2, label: '指定灰度' }
];
// 标签输入
const inputValue = ref('');
const tagInputVisible = ref(false);
const tagInputDom = ref<any>();

const handleTagClose = (tag: string) => {
  form.grayDate!.splice(form.grayDate!.indexOf(tag), 1);
};

const showTagInput = () => {
  tagInputVisible.value = true;
  nextTick(() => {
    tagInputDom.value!.focus();
  });
};
const handleTagInput = () => {
  if (inputValue.value) {
    if (form.grayDate?.length === 0 || form.grayDate!.indexOf(inputValue.value) === -1) {
      form.grayDate!.push(inputValue.value);
    } else {
      showMessage('标签已存在');
    }
  }
  tagInputVisible.value = false;
  inputValue.value = '';
};
</script>

<style lang="scss" scoped>
.page-block-title {
  font-size: 1.1rem;
  font-weight: bold;
  color: $rlt-primary;
  margin-bottom: 27px;
}
.page-block {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px 24px;
  box-sizing: border-box;
  padding: 6px 12px;
}
.page-block-item {
  display: flex;
  margin-bottom: 18px;
  .label {
    flex-shrink: 0;
    color: $rlt-second;
    display: inline-flex;
    align-items: center;
    justify-content: flex-end;
    padding-right: 12px;
    height: 32px;
    line-height: 32px;
    width: 120px;
  }
  .tags {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    align-items: center;
  }
}
</style>
