<template>
  <div class="sentence-view page-view">
    <div class="page-top">
      <div class="page-tl">
        <!-- 指定前台展示用户 -->
        <div class="cl-item">
          <span class="label">当前类型：</span>
          <el-select
            v-model="currentType"
            placeholder="选择展示类型"
            style="width: 150px; margin-right: 12px"
            @change="changeCurrent">
            <el-option v-for="item in typeList" :key="item.id" :label="item.label" :value="item.value"></el-option>
          </el-select>
          <el-button v-show="ccfBtnFlag" @click="updateCurrent">修改</el-button>
        </div>
      </div>
      <div class="page-tr">
        <el-input
          v-model="searchKey"
          placeholder="内容关键词(回车搜索)"
          @keyup.enter="
            (e: any) => {
              getPage();
              e.target.blur();
            }
          ">
          <template #prefix>
            <i class="ir-search"></i>
          </template>
        </el-input>
        <el-button type="primary" @click="openDialog('add')">添加句子</el-button>
        <el-button type="warning" @click="openBatch">批量添加</el-button>
      </div>
    </div>
    <div class="page-mid">
      <el-table :data="tableData" stripe style="min-width: 1000px" row-key="id">
        <el-table-column prop="id" label="ID" width="180" />
        <el-table-column prop="content" label="内容" min-width="240" />
        <el-table-column prop="disabled" label="可用性" width="72" class-name="inline-center">
          <template #default="{ row }">
            <span>{{ row.disabled ? '❌' : '✅' }}</span>
          </template>
        </el-table-column>
        <el-table-column prop="showDate" label="显示日期" width="120" />
        <el-table-column prop="updateTime" label="更新日期" />
        <el-table-column align="center" label="操作">
          <template #default="{ row }">
            <el-button type="primary" link @click="openDialog('edit', row)">编辑</el-button>
            <el-popconfirm
              width="220"
              confirm-button-text="确认"
              cancel-button-text="取消"
              icon-color="#e83929"
              title="您是否确认删除该项？"
              @confirm="deleteSentence(row.id)">
              <template #reference>
                <el-button type="danger" link>删除</el-button>
              </template>
            </el-popconfirm>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <div class="page-bottom">
      <el-pagination
        v-model:current-page="currentPage"
        v-model:page-size="pageSize"
        :page-sizes="[5, 10, 15, 20]"
        background
        layout="sizes, prev, pager, next"
        :total="total"
        hide-on-single-page
        @size-change="handleSizeChange"
        @current-change="handleCurrentChange" />
    </div>
    <!-- 新增、修改dialog -->
    <el-dialog v-model="dialogVisible" :title="dialogTitle" class="lradius">
      <el-form :model="dialogForm" :rules="formRules" ref="dialogDom" label-width="80">
        <el-form-item label="内容" prop="content">
          <el-input v-model="dialogForm.content" type="textarea" :rows="3" placeholder="请输入句子内容" />
        </el-form-item>
        <el-form-item label="是否禁用" prop="disabled">
          <el-switch v-model="dialogForm.disabled" style="--el-switch-on-color: #e83929"></el-switch>
        </el-form-item>
        <el-form-item label="显示日期" prop="showDate">
          <el-date-picker
            v-model="(dialogForm.showDate as string)"
            type="date"
            placeholder="选择特定展示日期"
            value-format="YYYY-MM-DD"
            :disabled-date="(date: Date) => date.getTime() < Date.now()" />
        </el-form-item>
      </el-form>
      <template #footer>
        <span class="dialog-footer">
          <el-button @click="dialogVisible = false">取消</el-button>
          <el-button type="primary" @click="confirm(dialogDom)">{{ dialogBtnText }}</el-button>
        </span>
      </template>
    </el-dialog>
    <!-- 批量添加 -->
    <el-dialog v-model="batchVisible" title="批量添加" class="lradius">
      <el-input
        type="textarea"
        v-model="batchJson"
        :rows="15"
        :placeholder="batchPlace"
        :class="{ 'is-error': batchTip !== '' }"
        @blur="validateJson"
        @keydown="caDown" />
      <span class="batch-tip" v-show="batchTip">{{ batchTip }}</span>
      <template #footer>
        <span class="dialog-footer">
          <el-button type="success" link @click="formatJson">格式化</el-button>
          <el-button @click="batchVisible = false">取消</el-button>
          <el-button type="primary" @click="batch">确认添加</el-button>
        </span>
      </template>
    </el-dialog>
  </div>
</template>

<script setup lang="ts">
import { onBeforeMount, reactive, ref } from 'vue';
import {
  ElSelect,
  ElButton,
  ElInput,
  ElOption,
  ElTable,
  ElTableColumn,
  ElPagination,
  ElPopconfirm,
  ElDialog,
  ElForm,
  ElFormItem,
  ElSwitch,
  ElDatePicker,
  type FormInstance,
  type FormRules
} from 'element-plus';
import { useCurrentInfoStore } from '@/stores/currentinfo';
import {
  addSentenceApi,
  batchSentenceApi,
  delSentenceApi,
  editSentenceApi,
  getSentencePageApi
} from '@/api/sentenceApi';
import type { Sentence } from '@/types';
import { setCurrentApi } from '@/api/currentApi';
import { isJson, showMessage } from '@/utils/commonUtil';

// dialog
const dialogVisible = ref(false);
const dialogTitle = ref('新增句子');
const dialogBtnText = ref('确认添加');
let dialogType = 'add';
// 表单
const dialogForm = ref<Sentence>({
  content: '',
  disabled: false,
  showDate: ''
});
const dialogDom = ref<FormInstance>();
const formRules = reactive<FormRules>({
  content: [
    { required: true, message: '内容不能为空', trigger: 'blur' },
    { max: 255, message: '句子不能超过255个字符', trigger: 'blur' }
  ]
});

// 每日一句展示类型
const currentType = ref(0);
const ccfBtnFlag = ref(false);

const typeList = [
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

const { getCurrentSentenceType, setCurrentSentenceType, getCurrentId } = useCurrentInfoStore();

const changeCurrent = () => {
  if (currentType.value === getCurrentSentenceType()) ccfBtnFlag.value = false;
  else ccfBtnFlag.value = true;
};

const updateCurrent = () => {
  setCurrentApi({
    id: getCurrentId(),
    sentenceType: currentType.value
  }).then(res => {
    if (res.code === 213) {
      showMessage('句子展示类型修改成功', 'success');
      setCurrentSentenceType(currentType.value);
      changeCurrent();
    } else {
      showMessage(res.msg, 'warning');
    }
  });
};

// 搜索
const searchKey = ref('');

const initForm = () => {
  dialogForm.value = {
    content: '',
    disabled: false,
    showDate: ''
  };
};
const openDialog = (type: 'add' | 'edit', data: any = null) => {
  dialogType = type;
  if (type === 'edit') {
    // 编辑
    dialogForm.value = data;
    dialogTitle.value = '编辑句子';
    dialogBtnText.value = '确认编辑';
  } else {
    // 新增
    initForm();
    dialogTitle.value = '新增句子';
    dialogBtnText.value = '确认添加';
  }
  dialogVisible.value = true;
};
const confirm = (frm: FormInstance | undefined) => {
  if (!frm) return;
  frm.validate(valid => {
    if (valid) {
      const data: Sentence = {
        content: dialogForm.value.content,
        disabled: dialogForm.value.disabled,
        showDate: dialogForm.value.showDate === '' ? null : dialogForm.value.showDate
      };
      dialogForm.value.id && (data['id'] = dialogForm.value.id);
      if (dialogType === 'edit') {
        // 编辑
        edit(data);
      } else {
        // 新增
        add(data);
      }
    }
  });
};

// 新增
const add = (data: Sentence) => {
  addSentenceApi(data).then(res => {
    if (res.code === 211) {
      showMessage('新增句子成功', 'success');
      dialogVisible.value = false;
      getPage();
    } else {
      showMessage(res.msg, 'warning');
    }
  });
};
// 编辑
const edit = (data: Sentence) => {
  editSentenceApi(data).then(res => {
    if (res.code === 213) {
      showMessage('修改句子成功', 'success');
      dialogVisible.value = false;
      getPage();
    } else {
      showMessage(res.msg, 'warning');
    }
  });
};

// 批量新增
const batchPlace =
  '输入规则(需满足下列条件)：\n- 数据包含在数组中，满足 json 格式\n- 每一项为一对象，结构如下\n- content 为必填项，类型为string；其它为可选项，其中，disabled 为布尔值；showDate 为 null 或 yyyy-MM-dd 格式的日期\n- 多项用逗号隔开\n例：\n[\n  {\n    "content": "句子内容，该项为必选项。",\n    "disabled": false,\n    "showDate": "2023-10-01"\n  }\n]\ntips：可以 Ctrl + Alt 组合键快速格式化';
const batchVisible = ref(false);
const batchJson = ref('');
const batchTip = ref('');
const openBatch = () => {
  batchVisible.value = true;
  batchJson.value = '';
};
const validateJson = () => {
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
    batchSentenceApi(json).then(res => {
      if (res.code === 211) {
        showMessage('批量添加成功', 'success');
        batchVisible.value = false;
        getPage();
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

// 表格数据
const tableData = ref<Sentence[]>([]);
const currentPage = ref(1);
const pageSize = ref(5);
const total = ref(0);

const getPage = () => {
  getSentencePageApi(currentPage.value, pageSize.value, searchKey.value).then(res => {
    if (res.data) {
      tableData.value = res.data.list;
      total.value = res.data.total;
    }
  });
};

onBeforeMount(() => {
  getPage();
});
const handleSizeChange = (val: number) => {
  pageSize.value = val;
  getPage();
};
const handleCurrentChange = (val: number) => {
  currentPage.value = val;
  getPage();
};

// 操作
const deleteSentence = (id: string) => {
  delSentenceApi(id).then(res => {
    if (res.code === 212) {
      showMessage('句子删除成功', 'success');
      getPage();
    } else {
      showMessage(res.msg, 'warning');
    }
  });
};
</script>

<style lang="scss" scoped></style>
