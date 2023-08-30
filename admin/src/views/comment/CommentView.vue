<template>
  <div class="comment-view page-view">
    <div class="page-top">
      <div class="page-tl">
        <!-- 搜索 -->
        <div class="cl-item">
          <span class="label">可见性：</span>
          <el-select v-model="visible" placeholder="可见性" style="width: 81px; margin-right: 12px">
            <el-option v-for="item in visibleList" :key="item.id" :label="item.label" :value="item.value"></el-option>
          </el-select>
        </div>
        <div class="cl-item">
          <span class="label">可用性：</span>
          <el-select v-model="disabled" placeholder="可用性" style="width: 81px; margin-right: 12px">
            <el-option v-for="item in disabledList" :key="item.id" :label="item.label" :value="item.value"></el-option>
          </el-select>
        </div>
        <div class="cl-item">
          <span class="label">类型：</span>
          <el-select v-model="searchType" placeholder="选择搜索类型" style="width: 81px; margin-right: 12px">
            <el-option v-for="item in typeList" :key="item.id" :label="item.label" :value="item.value"></el-option>
          </el-select>
        </div>
        <el-input
          v-model="searchKey"
          placeholder="请输入搜索内容"
          clearable
          style="width: 180px; margin-right: 12px"></el-input>
        <el-button type="primary" @click="search">搜索</el-button>
      </div>
      <div class="page-rl">
        <el-button type="primary" @click="openDialog('add')">添加留言</el-button>
      </div>
    </div>
    <div class="page-mid">
      <el-table :data="tableData" stripe style="min-width: 1000px" row-key="id">
        <el-table-column prop="nickname" label="昵称" width="120" />
        <el-table-column prop="email" label="邮箱" width="160" />
        <el-table-column prop="content" label="内容" min-width="210" />
        <el-table-column prop="address" label="地址" width="180">
          <template #default="{ row }">
            <span>{{ `${row.address ?? '未知'}[${row.ip ?? '未知IP'}]` }}</span>
          </template>
        </el-table-column>
        <el-table-column prop="visible" label="可见性" width="72" class-name="inline-center">
          <template #default="{ row }">
            <span>{{ row.visible ? '❌' : '✅' }}</span>
          </template>
        </el-table-column>
        <el-table-column prop="disabled" label="可用性" width="72" class-name="inline-center">
          <template #default="{ row }">
            <span>{{ row.disabled ? '❌' : '✅' }}</span>
          </template>
        </el-table-column>
        <el-table-column prop="updateTime" label="更新时间" width="165" />
        <el-table-column align="center" label="操作" width="120" class-name="inline-center clean">
          <template #default="{ row }">
            <el-button type="primary" link @click="openDialog('edit', row)">编辑</el-button>
            <el-popconfirm
              width="220"
              confirm-button-text="确认"
              cancel-button-text="取消"
              icon-color="#e83929"
              title="您是否确认删除该项？"
              @confirm="delComment(row.id)">
              <template #reference>
                <el-button type="danger" link>删除</el-button>
              </template>
            </el-popconfirm>
            <el-popconfirm
              width="220"
              confirm-button-text="确认"
              cancel-button-text="取消"
              icon-color="#f6ad49"
              :title="`您是否确认${row.visible ? '显示' : '隐藏'}该项？`"
              @confirm="editVisible(row)">
              <template #reference>
                <el-button type="info" link>{{ row.visible ? '显示' : '隐藏' }}</el-button>
              </template>
            </el-popconfirm>
            <el-popconfirm
              width="220"
              confirm-button-text="确认"
              cancel-button-text="取消"
              icon-color="#f6ad49"
              :title="`您是否确认${row.disabled ? '启用' : '禁用'}该项？`"
              @confirm="editDisabled(row)">
              <template #reference>
                <el-button type="warning" link>{{ row.disabled ? '启用' : '禁用' }}</el-button>
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
        <el-form-item label="昵称" prop="nickname">
          <el-input v-model="dialogForm.nickname" placeholder="请输入昵称" />
        </el-form-item>
        <el-form-item label="邮箱" prop="email">
          <el-input v-model="dialogForm.email" type="email" placeholder="请输入邮箱" />
        </el-form-item>
        <el-form-item label="内容" prop="content">
          <el-input v-model="dialogForm.content" type="textarea" :rows="3" placeholder="请输入留言内容" />
        </el-form-item>
        <el-form-item label="IP(V4)" prop="ip">
          <el-input v-model="dialogForm.ip" placeholder="请输入IP" style="width: 240px; margin-right: 8px" />
          <el-tooltip v-if="dialogType !== 'add'" effect="dark" content="后端自动获取IP" placement="top">
            <el-checkbox v-model="dialogForm.iprf" />
          </el-tooltip>
        </el-form-item>
        <el-form-item label="是否隐藏" prop="visible">
          <el-switch v-model="dialogForm.visible" style="--el-switch-on-color: #fac03d"></el-switch>
        </el-form-item>
        <el-form-item label="是否禁用" prop="disabled">
          <el-switch v-model="dialogForm.disabled" style="--el-switch-on-color: #e83929"></el-switch>
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
  ElCheckbox,
  ElTooltip,
  type FormInstance,
  type FormRules
} from 'element-plus';
import {
  getCommentPageApi,
  type CommentSearchType,
  editCommentApi,
  delCommentApi,
  addCommentApi
} from '@/api/commentApi';
import type { CommentInfo } from '@/types';
import { showMessage } from '@/utils/commonUtil';

/**
 * 是否合法IP地址
 * @param value
 * @param callback
 */
const validateIP = (rule: any, value: any, callback: (error?: string | Error | undefined) => void) => {
  const reg =
    /^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$/;
  if (!reg.test(value) && value !== '') {
    callback(new Error('请输入正确的IP地址'));
  } else {
    callback();
  }
};

// dialog
const dialogVisible = ref(false);
const dialogTitle = ref('新增留言');
const dialogBtnText = ref('确认添加');
let dialogType = 'add';
// 表单
const dialogForm = ref<CommentInfo>({
  nickname: '',
  email: '',
  content: '',
  ip: '',
  visible: false,
  disabled: false,
  iprf: false
});
const dialogDom = ref<FormInstance>();
const formRules = reactive<FormRules>({
  nickname: [
    { required: true, message: '昵称不能为空', trigger: 'blur' },
    { max: 64, message: '昵称不能超过64个字符', trigger: 'blur' }
  ],
  email: [
    { required: true, message: '邮箱不能为空', trigger: 'blur' },
    { type: 'email', message: '邮箱格式不正确', trigger: 'blur' }
  ],
  content: [
    { required: true, message: '内容不能为空', trigger: 'blur' },
    { max: 255, message: '留言不能超过255个字符', trigger: 'blur' }
  ],
  ip: [{ validator: validateIP, message: 'ip 格式错误', trigger: 'blur' }]
});

let editForm: CommentInfo = {};
const initForm = () => {
  dialogForm.value = {
    nickname: '',
    email: '',
    content: '',
    ip: '',
    visible: false,
    disabled: false,
    iprf: false
  };
};
const backupEditForm = (data: CommentInfo) => {
  editForm = JSON.parse(JSON.stringify(data));
};
const openDialog = (type: 'add' | 'edit', data: any = null) => {
  dialogType = type;
  if (type === 'edit') {
    // 编辑
    dialogForm.value = data;
    backupEditForm(data);
    dialogTitle.value = '编辑留言';
    dialogBtnText.value = '确认编辑';
  } else {
    // 新增
    initForm();
    dialogTitle.value = '新增留言';
    dialogBtnText.value = '确认添加';
  }
  dialogVisible.value = true;
};
const confirm = (frm: FormInstance | undefined) => {
  if (!frm) return;
  frm.validate(valid => {
    if (valid) {
      const data: any = {};

      if (dialogType === 'edit') {
        let key: keyof CommentInfo;
        for (key in dialogForm.value) {
          if (dialogForm.value[key] !== editForm[key]) {
            data[key] = dialogForm.value[key];
          }
        }
        dialogForm.value.id && (data['id'] = dialogForm.value.id);
        // 编辑
        edit(data);
      } else {
        let key: keyof CommentInfo;
        for (key in dialogForm.value) {
          if (dialogForm.value[key]) {
            data[key] = dialogForm.value[key];
          }
        }
        // 新增
        add(data);
      }
    }
  });
};

// 新增
const add = (data: CommentInfo) => {
  addCommentApi(data).then(res => {
    if (res.code === 211) {
      showMessage('新增留言成功', 'success');
      dialogVisible.value = false;
      getPage();
    } else {
      showMessage(res.msg, 'warning');
    }
  });
};
// 编辑
const edit = (data: CommentInfo) => {
  editCommentApi(data).then(res => {
    if (res.code === 213) {
      showMessage('修改留言成功', 'success');
      dialogVisible.value = false;
      getPage();
    } else {
      showMessage(res.msg, 'warning');
    }
  });
};

// 搜索功能
const searchKey = ref('');
const searchType = ref<CommentSearchType>('content');
const visible = ref<number>(2);
const disabled = ref<number>(2);

// 选项列表
const visibleList = [
  { id: 'a0a2e7766000b86d2e134506d395aad5', label: '默认', value: 2 },
  { id: '4e6d818a396a23786a54b0e794a68362', label: '显示', value: 0 },
  { id: '638a660a0c0ec3f44f35a65d0da23ec2', label: '隐藏', value: 1 }
];
const disabledList = [
  { id: '555167e81e19ed8091fffa27683aea54', label: '默认', value: 2 },
  { id: 'dabe2feaa4d4672003e7e5559d8630be', label: '正常', value: 0 },
  { id: '6549ce186d874aab6ea2cf8b96cc9016', label: '禁用', value: 1 }
];
const typeList = [
  { id: 'cdf12bdd7778142517122b642afdb9e3', label: '内容', value: 'content' },
  { id: '871652ade3b135f0c99b669b19173122', label: '昵称', value: 'nickname' },
  { id: 'bd49e2f100c1d95777c33bc05c781dd3', label: '邮箱', value: 'email' },
  { id: '7063510e566c2b92c694a1e500361111', label: '地址', value: 'address' }
];

const search = () => {
  getPage();
};

// 列表
const tableData = ref<CommentInfo[]>([]);
const currentPage = ref(1);
const pageSize = ref(5);
const total = ref(0);

const getPage = () => {
  getCommentPageApi(
    currentPage.value,
    pageSize.value,
    searchKey.value,
    searchType.value,
    visible.value,
    disabled.value
  ).then(res => {
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

// 删除
const delComment = (id: string) => {
  delCommentApi(id).then(res => {
    if (res.code === 212) {
      showMessage('删除留言成功', 'success');
      getPage();
    } else {
      showMessage(res.msg, 'warning');
    }
  });
};

// 禁用启用
const editDisabled = (data: CommentInfo) => {
  editCommentApi({
    id: data.id,
    disabled: !data.disabled
  }).then(res => {
    if (res.code === 213) {
      showMessage(`${data.disabled ? '启用' : '禁用'}留言成功`, 'success');
      getPage();
    } else {
      showMessage(res.msg, 'warning');
    }
  });
};
// 显示隐藏
const editVisible = (data: CommentInfo) => {
  editCommentApi({
    id: data.id,
    visible: !data.visible
  }).then(res => {
    if (res.code === 213) {
      showMessage(`${data.disabled ? '显示' : '隐藏'}留言成功`, 'success');
      getPage();
    } else {
      showMessage(res.msg, 'warning');
    }
  });
};
</script>

<style lang="scss" scoped></style>
