<template>
  <div class="social-view page-view">
    <div class="page-top">
      <div class="page-tl">
        <!-- 指定前台展示用户 -->
        <div class="cl-item">
          <span class="label">当前用户：</span>
          <el-select
            v-model="currentUserId"
            placeholder="选择展示用户"
            style="width: 150px; margin-right: 12px"
            @change="changeCurrent">
            <el-option v-for="item in userList" :key="item.id" :label="item.nickname" :value="item.id"></el-option>
          </el-select>
        </div>
      </div>
      <div class="page-tr">
        <el-button type="primary" @click="openDialog('add')">添加社交项</el-button>
      </div>
    </div>
    <div class="page-mid">
      <el-table :data="tableData" stripe style="min-width: 1000px" row-key="id">
        <el-table-column prop="id" label="ID" width="180" />
        <el-table-column prop="label" label="标签" />
        <el-table-column prop="link" label="链接">
          <template #default="{ row }">
            <el-link type="primary" :href="row.link" target="_blank">{{ row.link }}</el-link>
          </template>
        </el-table-column>
        <el-table-column prop="icon" label="图标" width="72" class-name="inline-center">
          <template #default="{ row }">
            <i :class="`ir-${row.icon}`" class="icon-color"></i>
          </template>
        </el-table-column>
        <el-table-column prop="disabled" label="可用性" width="72" class-name="inline-center">
          <template #default="{ row }">
            <span>{{ row.disabled ? '❌' : '✅' }}</span>
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
              icon-color="#f6ad49"
              :title="`您是否确认${row.disabled ? '启用' : '禁用'}该项？`"
              @confirm="editSocial(row)">
              <template #reference>
                <el-button type="warning" link>{{ row.disabled ? '启用' : '禁用' }}</el-button>
              </template>
            </el-popconfirm>

            <el-popconfirm
              width="220"
              confirm-button-text="确认"
              cancel-button-text="取消"
              icon-color="#e83929"
              title="您是否确认删除该项？"
              @confirm="deleteSocial(row.id)">
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
        <el-form-item label="标签" prop="label">
          <el-input v-model="dialogForm.label" placeholder="请输入标签"></el-input>
        </el-form-item>
        <el-form-item label="链接" prop="link">
          <el-input v-model="dialogForm.link" placeholder="请输入跳转链接"></el-input>
        </el-form-item>
        <el-form-item label="图标" prop="icon">
          <el-select v-model="dialogForm.icon" placeholder="请选择显示按钮" popper-class="icon-select-list">
            <el-option v-for="item in icons" :key="item.value" :label="item.label" :value="item.value">
              <i :class="`ir-${item.value}`" class="icon-color"></i>
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="排序" prop="sort">
          <el-input-number v-model="dialogForm.sort" :min="0" :max="999" controls-position="right" />
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
  ElOption,
  ElButton,
  ElLink,
  ElTable,
  ElTableColumn,
  ElPagination,
  ElPopconfirm,
  ElDialog,
  ElForm,
  ElFormItem,
  ElInput,
  ElInputNumber,
  ElSwitch,
  type FormInstance,
  type FormRules
} from 'element-plus';
import type { Social, UserShortInfo } from '@/types';
import { useCurrentInfoStore } from '@/stores/currentinfo';
import { addSocialApi, delSocialApi, editSocialApi, getSocialPageApi, getUserListApi } from '@/api/userApi';
import { showMessage } from '@/utils/commonUtil';
import { getSocialIcons } from '@/stores/icons';

// 测试
const icons = getSocialIcons();

const { getCurrentUserId } = useCurrentInfoStore();
const currentUserId = ref('');

const userList = ref<UserShortInfo[]>([]);
const getUserList = () => {
  currentUserId.value = getCurrentUserId();
  getUserListApi().then(res => {
    if (res.data) {
      userList.value = res.data;
    }
  });
};
const changeCurrent = () => {
  getPage();
};

onBeforeMount(() => {
  getUserList();
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

// 表格数据
const tableData = ref<Social[]>([]);
const currentPage = ref(1);
const pageSize = ref(5);
const total = ref(0);

const getPage = () => {
  getSocialPageApi(currentPage.value, pageSize.value, currentUserId.value).then(res => {
    if (res.code === 214) {
      tableData.value = res.data.list;
      total.value = res.data.total;
    } else {
      showMessage(res.msg, 'warning');
    }
  });
};

// 删除项
const deleteSocial = (id: string) => {
  delSocialApi(id).then(res => {
    if (res.code === 212) {
      showMessage('删除成功', 'success');
      getPage();
    } else {
      showMessage(res.msg, 'warning');
    }
  });
};

// 增 | 改
// dialog
const dialogVisible = ref(false);
const dialogTitle = ref('新增社交项');
const dialogBtnText = ref('确认添加');
let dialogType = 'add';
// 表单
const dialogForm = ref<Social>({
  icon: '',
  label: '',
  link: '',
  disabled: false,
  sort: 0,
  userId: ''
});
const dialogDom = ref<FormInstance>();
const formRules = reactive<FormRules>({
  label: [
    { required: true, message: '标签不能为空', trigger: 'blur' },
    { max: 16, message: '标签不能超过16个字符', trigger: 'blur' }
  ],
  icon: [{ required: true, message: '图标不能为空', trigger: 'blur' }]
});
const initForm = () => {
  dialogForm.value = {
    icon: '',
    label: '',
    link: '',
    disabled: false,
    sort: 0,
    userId: currentUserId.value
  };
};
let editForm: Social = { icon: '', label: '' };
const backupEdit = (data: Social) => {
  Object.assign(editForm, data);
};
const openDialog = (type: 'add' | 'edit', data: any = null) => {
  dialogType = type;
  if (type === 'edit') {
    // 编辑
    dialogForm.value = data;
    backupEdit(data);
    dialogTitle.value = '编辑社交信息';
    dialogBtnText.value = '确认编辑';
  } else {
    // 新增
    initForm();
    dialogTitle.value = '新增社交信息';
    dialogBtnText.value = '确认添加';
  }
  dialogVisible.value = true;
};
const confirm = (frm: FormInstance | undefined) => {
  if (!frm) return;
  frm.validate(valid => {
    if (valid) {
      let data: any = {};
      if (dialogType === 'edit') {
        // 编辑
        let key: keyof Social;
        for (key in dialogForm.value) {
          if (dialogForm.value[key] !== editForm[key]) {
            data[key] = dialogForm.value[key];
          }
        }
        data['id'] = dialogForm.value.id;
        edit(data);
      } else {
        // 新增
        data = dialogForm.value;
        add(data);
      }
    }
  });
};

// 新增
const add = (data: Social) => {
  addSocialApi(data).then(res => {
    if (res.code === 211) {
      showMessage('添加用户社交信息成功', 'success');
      dialogVisible.value = false;
      getPage();
    } else {
      showMessage(res.msg, 'warning');
    }
  });
};
// 编辑
const edit = (data: Social) => {
  editSocialApi(data).then(res => {
    if (res.code === 213) {
      showMessage('修改用户社交信息成功', 'success');
      dialogVisible.value = false;
      getPage();
    } else {
      showMessage(res.msg, 'warning');
    }
  });
};

const editSocial = (data: Social) => {
  editSocialApi({
    id: data.id,
    disabled: !data.disabled
  }).then(res => {
    if (res.code === 213) {
      showMessage(`${data.disabled ? '启用' : '禁用'}成功`, 'success');
      dialogVisible.value = false;
      getPage();
    } else {
      showMessage(res.msg, 'warning');
    }
  });
};
</script>

<style lang="scss" scoped></style>
