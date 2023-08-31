<template>
  <div class="product-label-view page-view">
    <div class="page-top">
      <div class="page-tl">
        <!-- 指定前台展示用户 -->
        <div class="cl-item">
          <span class="label">当前作品：</span>
          <el-select
            v-model="productId"
            placeholder="选择管理作品"
            style="width: 150px; margin-right: 12px"
            @change="changeCurrent">
            <el-option v-for="item in productList" :key="item.id" :label="item.name" :value="item.id"></el-option>
          </el-select>
        </div>
      </div>
      <div class="page-tr">
        <el-button type="primary" @click="openDialog('add')">添加标签</el-button>
      </div>
    </div>
    <div class="page-mid">
      <el-table :data="tableData" stripe style="min-width: 1000px" row-key="id">
        <el-table-column prop="id" label="ID" width="180" />
        <el-table-column prop="label" label="标签名称" width="160" />
        <el-table-column prop="icon" label="图标" width="72">
          <template #default="{ row }">
            <i :class="`ir-${row.icon}`"></i>
          </template>
        </el-table-column>
        <el-table-column prop="color" label="颜色" width="72">
          <template #default="{ row }">
            <el-tooltip effect="dark" :content="row.color" placement="top">
              <span class="color-block" :style="{ backgroundColor: row.color }"></span>
            </el-tooltip>
          </template>
        </el-table-column>
        <el-table-column prop="link" label="链接" min-width="210">
          <template #default="{ row }">
            <el-link :href="row.link" target="_blank" type="primary">{{ row.link }}</el-link>
          </template>
        </el-table-column>
        <el-table-column prop="sort" label="排序" width="72" />
        <el-table-column prop="disabled" label="可用性" width="72" class-name="inline-center">
          <template #default="{ row }">
            <span>{{ row.disabled ? '❌' : '✅' }}</span>
          </template>
        </el-table-column>
        <el-table-column prop="updateTime" label="更新时间" width="180" />
        <el-table-column align="center" label="操作" width="180" class-name="inline-center clean">
          <template #default="{ row }">
            <el-button type="primary" link @click="openDialog('edit', row)">编辑</el-button>
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
            <el-popconfirm
              width="220"
              confirm-button-text="确认"
              cancel-button-text="取消"
              icon-color="#e83929"
              title="您是否确认删除该项？"
              @confirm="delProductLabel(row.id)">
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
        <el-form-item label="标签名" prop="label">
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
        <el-form-item label="颜色" prop="color">
          <el-color-picker v-model="dialogForm.color" />
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
import { addPLabelApi, delPLabelApi, editPLabelApi, getPLabelPageApi, getProductListApi } from '@/api/productApi';
import type { ProductLabel, ProductShortInfo } from '@/types';
import { showMessage } from '@/utils/commonUtil';
import { onBeforeMount, reactive, ref } from 'vue';
import {
  ElSelect,
  ElOption,
  ElButton,
  ElTable,
  ElTableColumn,
  ElInput,
  ElPopconfirm,
  ElLink,
  ElPagination,
  ElTooltip,
  ElDialog,
  ElForm,
  ElFormItem,
  ElInputNumber,
  ElSwitch,
  ElColorPicker,
  type FormInstance,
  type FormRules
} from 'element-plus';
import { useViewParamsStore } from '@/stores/viewparams';
import { getAllIcons } from '@/stores/icons';

const { getProductId, setProductId } = useViewParamsStore();
const productList = ref<ProductShortInfo[]>([]);
const productId = ref('');
const getProductList = () => {
  productId.value = getProductId();
  getProductListApi().then(res => {
    if (res.data) {
      productList.value = res.data;
    }
  });
};
const changeCurrent = () => {
  getPage();
};

// 列表
const tableData = ref<ProductLabel[]>([]);
const currentPage = ref(1);
const pageSize = ref(5);
const total = ref(0);

const getPage = () => {
  getPLabelPageApi(currentPage.value, pageSize.value, productId.value).then(res => {
    if (res.code === 214) {
      tableData.value = res.data.list;
      total.value = res.data.total;
    } else {
      tableData.value = [];
      total.value = 0;
    }
  });
};
onBeforeMount(() => {
  getProductList();
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

// 增 | 改
const icons = getAllIcons();
// dialog
const dialogVisible = ref(false);
const dialogTitle = ref('新增作品标签');
const dialogBtnText = ref('确认添加');
let dialogType = 'add';
// 表单
const dialogForm = ref<ProductLabel>({
  icon: '',
  label: '',
  link: '',
  color: '#61677C',
  disabled: false,
  sort: 0
});
const dialogDom = ref<FormInstance>();
const formRules = reactive<FormRules>({
  label: [
    { required: true, message: '名称不能为空', trigger: 'blur' },
    { max: 16, message: '名称不能超过16个字符', trigger: 'blur' }
  ],
  icon: [{ required: true, message: '图标不能为空', trigger: 'blur' }]
});
const initForm = () => {
  dialogForm.value = {
    icon: '',
    label: '',
    link: '',
    color: '#61677C',
    disabled: false,
    sort: 0,
    productId: productId.value
  };
};
let editForm: ProductLabel = { icon: '', label: '' };
const backupEdit = (data: ProductLabel) => {
  editForm = JSON.parse(JSON.stringify(data));
};
const openDialog = (type: 'add' | 'edit', data: any = null) => {
  dialogType = type;
  if (type === 'edit') {
    // 编辑
    dialogForm.value = data;
    backupEdit(data);
    dialogTitle.value = '编辑作品标签';
    dialogBtnText.value = '确认编辑';
  } else {
    // 新增
    initForm();
    dialogTitle.value = '新增作品标签';
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
        let key: keyof ProductLabel;
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
const add = (data: ProductLabel) => {
  addPLabelApi(data).then(res => {
    if (res.code === 211) {
      showMessage('添加作品标签成功', 'success');
      dialogVisible.value = false;
      getPage();
    } else {
      showMessage(res.msg, 'warning');
    }
  });
};
// 编辑
const edit = (data: ProductLabel) => {
  editPLabelApi(data).then(res => {
    if (res.code === 213) {
      showMessage('修改作品标签成功', 'success');
      dialogVisible.value = false;
      getPage();
    } else {
      showMessage(res.msg, 'warning');
    }
  });
};

// 删除
const delProductLabel = (id: string) => {
  delPLabelApi(id).then(res => {
    if (res.code === 212) {
      showMessage('删除作品标签成功', 'success');
      getPage();
    } else {
      showMessage(res.msg, 'warning');
    }
  });
};

// 禁用启用
const editDisabled = (data: ProductLabel) => {
  editPLabelApi({
    id: data.id,
    disabled: !data.disabled
  }).then(res => {
    if (res.code === 213) {
      showMessage(`${data.disabled ? '启用' : '禁用'}作品标签成功`, 'success');
      getPage();
    } else {
      showMessage(res.msg, 'warning');
    }
  });
};
</script>

<style lang="scss" scoped>
.color-block {
  width: 24px;
  height: 24px;
  border-radius: 50%;
  border: 1px solid $rlbd-default;
  display: inline-block;
  box-shadow: -2px -2px 3px 0 $wshadow70, 2px 2px 3px 0 $bshadow15;
}
</style>
