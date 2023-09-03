<template>
  <div class="role-view page-view">
    <div class="page-rtop">
      <el-button type="primary" @click="openDialog('add')" :disabled="selectRoleList.length <= 0">添加角色</el-button>
    </div>
    <div class="page-mid">
      <el-table :data="tableData" stripe style="min-width: 1000px" row-key="id">
        <el-table-column type="index" width="50" />
        <el-table-column prop="username" label="用户名" />
        <el-table-column prop="roleText" label="身份权限" />
        <el-table-column prop="disabled" label="可用性" width="72" class-name="inline-center">
          <template #default="{ row }">
            <span>{{ row.disabled ? '❌' : '✅' }}</span>
          </template>
        </el-table-column>
        <el-table-column align="center" label="操作" width="210">
          <template #default="{ row }">
            <el-button type="primary" link @click="openDialog('edit', row)" :disabled="row.role <= getRole()"
              >编辑</el-button
            >
            <el-popconfirm
              width="220"
              confirm-button-text="确认"
              cancel-button-text="取消"
              icon-color="#f6ad49"
              :title="`您是否确认${row.disabled ? '启用' : '禁用'}该项？`"
              @confirm="editDisabled(row)">
              <template #reference>
                <el-button type="warning" link :disabled="row.role <= getRole()">{{
                  row.disabled ? '启用' : '禁用'
                }}</el-button>
              </template>
            </el-popconfirm>
            <el-popconfirm
              width="220"
              confirm-button-text="确认"
              cancel-button-text="取消"
              icon-color="#e83929"
              title="您是否确认删除该项？"
              @confirm="deleteRole(row.id)">
              <template #reference>
                <el-button type="danger" link :disabled="row.role <= getRole()">删除</el-button>
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
        <el-form-item label="用户名" prop="username">
          <el-input v-model="dialogForm.username" placeholder="请输入角色用户名" />
        </el-form-item>
        <el-form-item label="密码" prop="password">
          <el-input v-model="dialogForm.password" placeholder="请输入角色密码" show-password clearable />
        </el-form-item>
        <el-form-item label="权限" prop="role">
          <el-select v-model="dialogForm.role" placeholder="请选择角色权限" :disabled="selectRoleList.length <= 0">
            <el-option
              v-for="item in selectRoleList"
              :key="item.id"
              :label="item.label"
              :value="item.value"
              :disabled="item.value <= getRole()" />
          </el-select>
        </el-form-item>
        <el-form-item label="可用性：" prop="disabled">
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

    <!-- 添加成功的提示dialog -->
    <el-dialog v-model="tipDialogVisible" title="请注意" class="lradius" :close-on-click-modal="false">
      <div class="tip-box">
        <div class="tip-item">
          <span class="tip-item_label">用户名：</span>
          <div class="tip-item_content">{{ tipData.username }}</div>
        </div>
        <div class="tip-item">
          <span class="tip-item_label">密码：</span>
          <div class="tip-item_content">
            <span>{{ tipPassVisible ? tipData.password : '••••••••' }}</span>
            <i class="tip-item_icon r" @click="tipPassVisible = !tipPassVisible">
              <svg v-if="tipPassVisible" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg">
                <path
                  fill="currentColor"
                  d="M512 160c320 0 512 352 512 352S832 864 512 864 0 512 0 512s192-352 512-352zm0 64c-225.28 0-384.128 208.064-436.8 288 52.608 79.872 211.456 288 436.8 288 225.28 0 384.128-208.064 436.8-288-52.608-79.872-211.456-288-436.8-288zm0 64a224 224 0 1 1 0 448 224 224 0 0 1 0-448zm0 64a160.192 160.192 0 0 0-160 160c0 88.192 71.744 160 160 160s160-71.808 160-160-71.744-160-160-160z"></path>
              </svg>
              <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024">
                <path
                  fill="currentColor"
                  d="M876.8 156.8c0-9.6-3.2-16-9.6-22.4-6.4-6.4-12.8-9.6-22.4-9.6-9.6 0-16 3.2-22.4 9.6L736 220.8c-64-32-137.6-51.2-224-60.8-160 16-288 73.6-377.6 176C44.8 438.4 0 496 0 512s48 73.6 134.4 176c22.4 25.6 44.8 48 73.6 67.2l-86.4 89.6c-6.4 6.4-9.6 12.8-9.6 22.4 0 9.6 3.2 16 9.6 22.4 6.4 6.4 12.8 9.6 22.4 9.6 9.6 0 16-3.2 22.4-9.6l704-710.4c3.2-6.4 6.4-12.8 6.4-22.4Zm-646.4 528c-76.8-70.4-128-128-153.6-172.8 28.8-48 80-105.6 153.6-172.8C304 272 400 230.4 512 224c64 3.2 124.8 19.2 176 44.8l-54.4 54.4C598.4 300.8 560 288 512 288c-64 0-115.2 22.4-160 64s-64 96-64 160c0 48 12.8 89.6 35.2 124.8L256 707.2c-9.6-6.4-19.2-16-25.6-22.4Zm140.8-96c-12.8-22.4-19.2-48-19.2-76.8 0-44.8 16-83.2 48-112 32-28.8 67.2-48 112-48 28.8 0 54.4 6.4 73.6 19.2L371.2 588.8ZM889.599 336c-12.8-16-28.8-28.8-41.6-41.6l-48 48c73.6 67.2 124.8 124.8 150.4 169.6-28.8 48-80 105.6-153.6 172.8-73.6 67.2-172.8 108.8-284.8 115.2-51.2-3.2-99.2-12.8-140.8-28.8l-48 48c57.6 22.4 118.4 38.4 188.8 44.8 160-16 288-73.6 377.6-176C979.199 585.6 1024 528 1024 512s-48.001-73.6-134.401-176Z"></path>
                <path
                  fill="currentColor"
                  d="M511.998 672c-12.8 0-25.6-3.2-38.4-6.4l-51.2 51.2c28.8 12.8 57.6 19.2 89.6 19.2 64 0 115.2-22.4 160-64 41.6-41.6 64-96 64-160 0-32-6.4-64-19.2-89.6l-51.2 51.2c3.2 12.8 6.4 25.6 6.4 38.4 0 44.8-16 83.2-48 112-32 28.8-67.2 48-112 48Z"></path>
              </svg>
            </i>
          </div>
        </div>
        <div class="tip-item">
          <i class="ir-info"></i>
          <span
            >请确保自己已经记住该用户名及密码再关闭该弹窗。<br />这是该角色登录系统的唯一凭证，密码只此显示一次，后续将不可再获取显示，如忘记，只能请求更高权限的角色进行重置修改！！！</span
          >
        </div>
      </div>
      <template #footer>
        <span class="dialog-footer">
          <el-button type="primary" @click="tipDialogVisible = false">记住了</el-button>
        </span>
      </template>
    </el-dialog>
  </div>
</template>

<script setup lang="ts">
import { computed, onBeforeMount, reactive, ref } from 'vue';
import {
  ElButton,
  ElTable,
  ElTableColumn,
  ElDialog,
  ElForm,
  ElFormItem,
  ElInput,
  ElPagination,
  ElSelect,
  ElOption,
  ElSwitch,
  ElPopconfirm,
  type FormInstance,
  type FormRules
} from 'element-plus';
import { showMessage, checkUsername, checkPassword } from '@/utils/commonUtil';
import { addRoleApi, delRoleApi, editRoleApi, getRolePageApi } from '@/api/roleApi';
import type { RoleInfo } from '@/types';
import { useRoleInfoStore } from '@/stores/roleinfo';

// 注意dialog
const tipDialogVisible = ref(false);
const tipPassVisible = ref(false);
const tipData = ref<any>({});

const { getRole } = useRoleInfoStore();

const roleList = [
  { id: '3d37a924cc00ad88e43845665ce39982', value: 0, label: '所有者' },
  { id: 'f85c4c423c6cfa0d99019005b003376d', value: 1, label: '管理' }
  // {id: '326c832b57cf5e4bfd5550c8756c7803', value: 2, label: '游客'}
];

const selectRoleList = computed(() => {
  return roleList.filter(item => {
    return item.value > getRole();
  });
});

// dialog
const dialogVisible = ref(false);
const dialogTitle = ref('新增角色');
const dialogBtnText = ref('确认添加');
let dialogType = 'add';
// 表单
const dialogForm = ref<RoleInfo>({});
const dialogDom = ref<FormInstance>();
const formRules = reactive<FormRules>({
  username: [
    { required: true, message: '用户名不能为空', trigger: 'blur' },
    { min: 8, message: '用户名不能低于8个字符', trigger: 'blur' },
    { max: 255, message: '用户名过长', trigger: 'blur' },
    { validator: checkUsername, trigger: 'blur' }
  ],
  password: [
    { required: true, message: '密码不能为空', trigger: 'blur' },
    { min: 8, message: '密码不能低于8个字符', trigger: 'blur' },
    { max: 255, message: '密码过长', trigger: 'blur' },
    { validator: checkPassword, trigger: 'blur' }
  ],
  role: [{ required: true, message: '角色权限不能为空', trigger: 'blur' }]
});

const initForm = () => {
  dialogForm.value = {
    username: '',
    password: '',
    disabled: false
  };
};
let editForm: RoleInfo = {};
const backupEdit = (data: RoleInfo) => {
  editForm = JSON.parse(JSON.stringify(data));
};
const openDialog = (type: 'add' | 'edit', data: any = null) => {
  dialogType = type;
  if (type === 'edit') {
    // 编辑
    data['password'] = '12345678';
    dialogForm.value = data;
    backupEdit(data);
    dialogTitle.value = '编辑角色';
    dialogBtnText.value = '确认编辑';
  } else {
    // 新增
    initForm();
    dialogTitle.value = '新增角色';
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
  const data: RoleInfo = {
    username: dialogForm.value['username'],
    password: dialogForm.value['password'],
    role: dialogForm.value['role'],
    disabled: dialogForm.value['disabled']
  };
  addRoleApi(data).then(res => {
    if (res.code === 211) {
      showMessage('新增角色成功', 'success');
      dialogVisible.value = false;

      tipData.value = {
        username: dialogForm.value['username'],
        password: dialogForm.value['password']
      };
      tipDialogVisible.value = true;
      getPage();
    } else {
      showMessage(res.msg, 'warning');
    }
  });
};
// 编辑
const edit = () => {
  const data: any = {};
  let key: keyof RoleInfo;
  for (key in dialogForm.value) {
    console.log(key, JSON.stringify(dialogForm.value[key]), JSON.stringify(editForm[key]));
    if (JSON.stringify(dialogForm.value[key]) !== JSON.stringify(editForm[key])) {
      data[key] = dialogForm.value[key];
    }
  }
  dialogForm.value.id && (data['id'] = dialogForm.value.id);
  editRoleApi(data).then(res => {
    if (res.code === 213) {
      showMessage('修改角色成功', 'success');
      dialogVisible.value = false;
      if (data['password']) {
        tipData.value = {
          username: dialogForm.value['username'],
          password: dialogForm.value['password']
        };
        tipDialogVisible.value = true;
      }
      getPage();
    } else {
      showMessage(res.msg, 'warning');
    }
  });
};

// 表格数据
const tableData = ref<RoleInfo[]>([]);
const currentPage = ref(1);
const pageSize = ref(5);
const total = ref(0);

const getPage = () => {
  getRolePageApi(currentPage.value, pageSize.value).then(res => {
    if (res.code === 214) {
      tableData.value = res.data.list;
      total.value = res.data.total;
    } else {
      tableData.value = [];
      total.value = 0;
    }
  });
};

const deleteRole = (id: string) => {
  delRoleApi(id).then(res => {
    if (res.code === 212) {
      showMessage('删除成功', 'success');
      getPage();
    } else {
      showMessage(res.msg, 'warning');
    }
  });
};

// 禁用启用
const editDisabled = (data: RoleInfo) => {
  editRoleApi({
    id: data.id,
    disabled: !data.disabled
  }).then(res => {
    if (res.code === 213) {
      showMessage(`${data.disabled ? '启用' : '禁用'}角色成功`, 'success');
      getPage();
    } else {
      showMessage(res.msg, 'warning');
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
</script>

<style lang="scss" scoped>
.tip-box {
  display: flex;
  flex-direction: column;
  align-items: center;
}
.tip-item {
  display: flex;
  align-items: center;
  & + .tip-item {
    margin-top: 16px;
  }
  .tip-item_label {
    width: 80px;
    display: inline-flex;
    justify-content: flex-end;
    align-items: center;
    margin-right: 12px;
    color: $rlt-second;
  }
  .tip-item_content {
    position: relative;
    min-width: 240px;
    height: 27px;
    display: inline-flex;
    align-items: center;
    box-sizing: border-box;
    padding: 3px 8px;
    color: $rlt-title;
    border-bottom: 1px solid $rlbd-default;
  }
  .tip-item_icon {
    width: 1em;
    height: 1em;
    user-select: none;
    cursor: pointer;
    position: absolute;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #a8abb2;
    &.r {
      right: 0;
    }
    svg {
      width: 1em;
      height: 1em;
    }
  }
  .ir-info {
    margin-right: 5px;
    color: $rlc-warning;
  }
}
</style>
