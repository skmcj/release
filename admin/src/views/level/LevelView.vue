<template>
  <div class="level-view page-view">
    <div class="page-top">
      <div class="page-tl">
        <!-- 指定前台展示用户 -->
        <div class="cl-item">
          <span class="label">当前境界组：</span>
          <el-select
            v-model="currentLevelId"
            placeholder="选择展示境界组"
            style="width: 150px; margin-right: 12px"
            @change="changeCurrent">
            <el-option v-for="item in levelList" :key="item.id" :label="item.tip" :value="item.id"></el-option>
          </el-select>
          <el-button v-show="ccfBtnFlag" @click="updateCurrent">修改</el-button>
        </div>
      </div>
      <div class="page-tr">
        <el-button type="primary" @click="openDialog('add')">添加境界组</el-button>
      </div>
    </div>
    <div class="page-mid">
      <el-table :data="tableData" stripe style="min-width: 1000px" row-key="id">
        <el-table-column prop="id" label="ID" width="180" />
        <el-table-column prop="tip" label="标识" width="120" />
        <el-table-column prop="labels" label="标签" class-name="levels" min-width="360">
          <template #default="{ row }">
            <el-tag v-for="(label, index) in row.labels" :key="`${row.id}-${index}`">
              {{ label }}
            </el-tag>
          </template>
        </el-table-column>
        <el-table-column prop="sort" label="排序" width="72" />
        <el-table-column prop="updateTime" label="更新时间" width="180" />
        <el-table-column align="center" label="操作">
          <template #default="{ row }">
            <el-button type="primary" link @click="openDialog('edit', row)">编辑</el-button>
            <el-popconfirm
              width="220"
              confirm-button-text="确认"
              cancel-button-text="取消"
              icon-color="#e83929"
              title="您是否确认删除该项？"
              @confirm="deleteLevel(row.id)">
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
        <el-form-item label="标识" prop="tip">
          <el-input v-model="dialogForm.tip" placeholder="请输入境界组标识" />
        </el-form-item>
        <el-form-item label="标识" prop="labels" class="tag-box">
          <!-- 标签列表 -->
          <el-tag
            v-for="tag in dialogForm.labels"
            :key="tag"
            closable
            :disable-transitions="false"
            @close="handleTagClose(tag)"
            >{{ tag }}</el-tag
          >
          <!-- 标签输入框 -->
          <el-input
            v-if="tagInputVisible"
            ref="tagInputDom"
            v-model="inputValue"
            size="small"
            style="width: 72px"
            @keyup.enter="handleTagInput" />
          <!-- 标签添加按钮 -->
          <el-button v-else style="width: 72px" size="small" @click="showTagInput"> + 添加 </el-button>
        </el-form-item>
        <el-form-item label="排序" prop="sort">
          <el-input-number
            v-model.number="dialogForm.sort"
            :min="0"
            controls-position="right"
            placeholder="请输入排序依据" />
        </el-form-item>
      </el-form>
      <template #footer>
        <span class="dialog-footer">
          <el-button @click="dialogVisible = false">取消</el-button>
          <el-button type="primary" @click="confirm(dialogDom)">{{ dialogBtnText }}</el-button>
        </span>
      </template>
    </el-dialog>
  </div>
</template>

<script setup lang="ts">
import { nextTick, onBeforeMount, reactive, ref } from 'vue';
import {
  ElSelect,
  ElOption,
  ElButton,
  ElTable,
  ElTableColumn,
  ElTag,
  ElPagination,
  ElPopconfirm,
  ElDialog,
  ElForm,
  ElFormItem,
  ElInput,
  ElInputNumber,
  type FormInstance,
  type FormRules
} from 'element-plus';
import type { LevelInfo, LevelShortInfo } from '@/types';
import { useCurrentInfoStore } from '@/stores/currentinfo';
import { addLevelApi, delLevelApi, editLevelApi, getLevelListApi, getLevelPageApi } from '@/api/levelApi';
import { setCurrentApi } from '@/api/currentApi';
import { showMessage } from '@/utils/commonUtil';

// 标签输入
const inputValue = ref('');
const tagInputVisible = ref(false);
const tagInputDom = ref<InstanceType<typeof ElInput>>();

const handleTagClose = (tag: string) => {
  dialogForm.value.labels!.splice(dialogForm.value.labels!.indexOf(tag), 1);
};

const showTagInput = () => {
  tagInputVisible.value = true;
  nextTick(() => {
    tagInputDom.value!.input!.focus();
  });
};

const handleTagInput = () => {
  if (dialogForm.value.labels?.length === 12) {
    showMessage('标签不能超过12个', 'warning');
  } else if (inputValue.value) {
    if (dialogForm.value.labels?.length === 0 || dialogForm.value.labels!.indexOf(inputValue.value) === -1) {
      dialogForm.value.labels!.push(inputValue.value);
    } else {
      showMessage('标签已存在');
    }
  }
  tagInputVisible.value = false;
  inputValue.value = '';
};

// dialog
const dialogVisible = ref(false);
const dialogTitle = ref('新增境界组');
const dialogBtnText = ref('确认添加');
let dialogType = 'add';
// 表单
const dialogForm = ref<LevelInfo>({});
const dialogDom = ref<FormInstance>();
const formRules = reactive<FormRules>({
  tip: [{ required: true, message: '标识不能为空', trigger: 'blur' }],
  labels: [
    { required: true, type: 'array', min: 2, message: '标签不能少于 2 个', trigger: 'blur' },
    { type: 'array', max: 12, message: '标签不能超过 12 个', trigger: 'blur' }
  ]
});

const initForm = () => {
  dialogForm.value = {
    tip: '',
    labels: [],
    sort: 0
  };
};
let editForm: LevelInfo = {};
const backupEdit = (data: LevelInfo) => {
  editForm = JSON.parse(JSON.stringify(data));
};
const openDialog = (type: 'add' | 'edit', data: any = null) => {
  dialogType = type;
  if (type === 'edit') {
    // 编辑
    dialogForm.value = data;
    backupEdit(data);
    dialogTitle.value = '编辑境界组';
    dialogBtnText.value = '确认编辑';
  } else {
    // 新增
    initForm();
    dialogTitle.value = '新增境界组';
    dialogBtnText.value = '确认添加';
  }
  dialogVisible.value = true;
};
const confirm = (frm: FormInstance | undefined) => {
  if (!frm) return;
  frm.validate(valid => {
    if (valid) {
      if (dialogType === 'edit') {
        // 编辑
        edit();
      } else {
        // 新增
        add();
      }
    }
  });
};

// 新增
const add = () => {
  const data: LevelInfo = {
    tip: dialogForm.value.tip,
    labels: dialogForm.value.labels,
    sort: dialogForm.value.sort
  };
  addLevelApi(data).then(res => {
    if (res.code === 211) {
      showMessage('新增境界组成功', 'success');
      dialogVisible.value = false;
      getPage();
      getLevelList();
    } else {
      showMessage(res.msg, 'warning');
    }
  });
};
// 编辑
const edit = () => {
  const data: any = {};
  let key: keyof LevelInfo;
  for (key in dialogForm.value) {
    console.log(key, JSON.stringify(dialogForm.value[key]), JSON.stringify(editForm[key]));
    if (JSON.stringify(dialogForm.value[key]) !== JSON.stringify(editForm[key])) {
      data[key] = dialogForm.value[key];
    }
  }
  dialogForm.value.id && (data['id'] = dialogForm.value.id);
  editLevelApi(data).then(res => {
    if (res.code === 213) {
      showMessage('修改境界组成功', 'success');
      dialogVisible.value = false;
      getPage();
    } else {
      showMessage(res.msg, 'warning');
    }
  });
};

const { getCurrentLevelId, setCurrentLevelId, getCurrentId } = useCurrentInfoStore();

const currentLevelId = ref('');
const ccfBtnFlag = ref(false);

// 用户缩略列表
const levelList = ref<LevelShortInfo[]>([]);

const getLevelList = () => {
  currentLevelId.value = getCurrentLevelId();
  getLevelListApi().then(res => {
    if (res.data) {
      levelList.value = res.data;
    }
  });
};

const changeCurrent = () => {
  if (currentLevelId.value === getCurrentLevelId()) ccfBtnFlag.value = false;
  else ccfBtnFlag.value = true;
};

const updateCurrent = () => {
  setCurrentApi({
    id: getCurrentId(),
    levelId: currentLevelId.value
  }).then(res => {
    if (res.code === 213) {
      showMessage('展示境界组修改成功', 'success');
      setCurrentLevelId(currentLevelId.value);
      changeCurrent();
    } else {
      showMessage(res.msg, 'warning');
    }
  });
};

// 表格数据
const tableData = ref<LevelInfo[]>([]);
const currentPage = ref(1);
const pageSize = ref(5);
const total = ref(0);

const getPage = () => {
  getLevelPageApi(currentPage.value, pageSize.value).then(res => {
    if (res.data) {
      tableData.value = res.data.list;
      total.value = res.data.total;
    }
  });
};

const deleteLevel = (id: string) => {
  delLevelApi(id).then(res => {
    if (res.code === 212) {
      showMessage('删除成功', 'success');
      getPage();
      getLevelList();
    } else {
      showMessage(res.msg, 'warning');
    }
  });
};

onBeforeMount(() => {
  getLevelList();
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
</script>

<style lang="scss" scoped></style>
